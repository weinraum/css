<?php namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\I18n\Time;

/**
 * Wine_model (neu, sicher, CI4-konform)
 *
 * Ziele:
 *  - Sichere Queries (keine konkatenierte SQL-Strings)
 *  - Prepared/Bindings via Query Builder
 *  - N+1 vermeiden (Trauben in einem Rutsch)
 *  - Kompatible Rückgaben (Altcode weiter nutzbar)
 */
class Wine_model extends Model
{
    protected $DBGroup      = 'default';
    protected $table        = 'wr_product';
    protected $primaryKey   = 'ID';
    protected $returnType   = 'array';
    protected $useTimestamps= false;

    // ----------------------------------------
    // Hilfsfunktionen
    // ----------------------------------------

public function builder(?string $table = null): BaseBuilder
{
    if ($table === null) {
        // nutzt CI4s Standard-Builder (beachtet $this->table etc.)
        return parent::builder();
    }
    // gezielt anderes Table
    return $this->db->table($table);
}

    /** Ausdruck für verfügbaren Bestand (stock_act - invoiced) */
    protected function stockAvailExpr(): string
    {
        return '(wr_product.stock_act - wr_product.invoiced)';
    }

    /** Whitelist + Aliase für Order-By */
    protected array $allowedOrder = [
        'wr_product.ID',
        'wr_product.stock_act',
        'wr_product.anz_mail_2mon',
    ];

    /** Logische Aliase (user-freundlich) -> echte Spalten/Expressions */
    protected array $orderAliases = [
        'id'            => 'wr_product.ID',
        'stock_act'     => 'wr_product.stock_act',
        'stock'         => 'STOCK_AVAIL_EXPR',
        'stock_avail'   => 'STOCK_AVAIL_EXPR',
        'no_stock'      => 'STOCK_AVAIL_EXPR',
        'anz_mail_2mon' => 'wr_product.anz_mail_2mon',
    ];

    protected function safeOrderBy(BaseBuilder $b, ?string $orderBy, string $defaultCol = 'wr_product.ID', string $defaultDir = 'DESC'): void
    {
        if (!$orderBy) { $b->orderBy($defaultCol, $defaultDir); return; }

        // Erwartet z.B. "stock desc" oder "anz_mail_2mon ASC"
        [$colRaw, $dirRaw] = array_pad(preg_split('/\s+/', trim($orderBy)), 2, null);
        $dir = in_array(strtoupper((string)$dirRaw), ['ASC','DESC'], true) ? strtoupper($dirRaw) : $defaultDir;

        $key    = strtolower((string)$colRaw);
        $mapped = $this->orderAliases[$key] ?? null;

        if ($mapped === 'STOCK_AVAIL_EXPR') {
            $b->orderBy($this->stockAvailExpr(), $dir, false); // false = nicht escapen (Formel)
            return;
        }

        $col = $mapped ?? (in_array($colRaw, $this->allowedOrder, true) ? $colRaw : $defaultCol);
        $b->orderBy($col, $dir);
    }

    /** Gemeinsame Sichtbarkeit: online + ggf. Bestand (verfügbar) */
    protected function applyVisibilityFilters(BaseBuilder $b, ?int $stock = null, ?int $online = 1): void
    {
        if ($online === 1)      { $b->where('wr_product.online', 1); }
        elseif ($online === 0)  { $b->where('wr_product.online', 0); }
        // -1 => ignorieren

        if     ($stock === 1) { $b->where($this->stockAvailExpr().' >', 0, false); }
        elseif ($stock === 2) { $b->where($this->stockAvailExpr().' >', 6, false); }
    }

    /** Netto/TVA + verfügbarer Bestand (einheitlich) */
    protected function enrichBasicCalc(array &$row, array $tva): void
    {
        // verfügbarer Bestand (physisch minus invoiced)
        $stockAvail          = (int)$row['stock_act'] - (int)$row['invoiced'];
        $row['stock_avail']  = $stockAvail;
        $row['no_stock']     = $stockAvail; // Legacy-Alias

        // Preise
        $tvaClass = (int)($row['wr_tva_class_ID'] ?: 1);
        $rate     = isset($tva[$tvaClass]) ? (float)$tva[$tvaClass] : 0.0;
        $brutto   = (float)$row['price'];

        $row['price_net'] = $rate > 0 ? ($brutto / (1.0 + $rate/100.0)) : $brutto;
        $row['price_tva'] = $brutto * ($rate/100.0);
    }

    /** Trauben für viele Produkte in einem Schlag laden (kompakt) */
    protected function mapGrapesForProducts(array $productIDs): array
    {
        if (empty($productIDs)) return [];

        $b = $this->builder('wr_grapes2wine');
        $b->select('wr_grapes2wine.product_ID, wr_grapes2wine.grape_ID, wr_grapes2wine.percent, wr_grapes.grape')
          ->join('wr_grapes', 'wr_grapes.ID = wr_grapes2wine.grape_ID')
          ->whereIn('wr_grapes2wine.product_ID', array_map('intval', $productIDs))
          ->orderBy('wr_grapes2wine.product_ID', 'ASC')
          ->orderBy('wr_grapes2wine.percent', 'DESC');

        $res = $b->get()->getResultArray();
        $out = [];
        foreach ($res as $r) {
            $pid = (int)$r['product_ID'];
            $gid = (int)$r['grape_ID'];
            $out[$pid][$gid] = [
                'percent' => (float)$r['percent'],
                'grape'   => $r['grape'],
            ];
        }
        return $out;
    }

    /** TVA-Tabelle holen (einmal pro Request) */
    protected function getTvaTable(): array
    {
        $b = $this->builder('wr_tva_class');
        if ($this->db->tableExists('wr_tva_class')) {
            $b->select('ID, tva')->orderBy('ID', 'ASC');
            $map = [];
            foreach ($b->get()->getResultArray() as $row) {
                $map[(int)$row['ID']] = (float)$row['tva'];
            }
            if ($map) return $map;
        }
        // Fallback: 19% als Klasse 1
        return [1 => 19.0];
    }

    // ----------------------------------------
    // Core-Reads (neu)
    // ----------------------------------------

    /**
     * getLatest: alle online & stock>0 Produkte, optional limitiert
     * Rückgabe wie bisher: Array [ID => row + prodID + Grapes + no_stock ...]
     */
    public function get_Latest(?int $_limit = null): array
    {
        $b = $this->builder('wr_product');
        $b->select('wr_product.*, wr_product.ID as prodID, wr_product.producer_ID')
          ->where('wr_product.online', 1)
          ->where($this->stockAvailExpr().' >', 0, false)
          ->orderBy('wr_product.ID', 'DESC');

        if ($_limit !== null) $b->limit((int)$_limit);

        $rows = $b->get()->getResultArray();
        if (!$rows) return [];

        $ids = array_column($rows, 'prodID');
        $grapesMap = $this->mapGrapesForProducts($ids);
        $tva = $this->getTvaTable();

        $out = [];
        foreach ($rows as $r) {
            $pid = (int)$r['prodID'];
            $this->enrichBasicCalc($r, $tva);
            if (isset($grapesMap[$pid])) {
                $r['Grapes'] = $grapesMap[$pid];
            }
            // Region/Producer-Labels (leichtgewichtige Anreicherung)
            $r = $this->attachRegionProducerLabels($r);

            $out[$pid] = $r;
        }
        return $out;
    }

    // === bestehende get_Wine ersetzen (optional) ===
    public function get_Wine(int $prodID, ?bool $_array = null)
    {
        // Alt-Verhalten: $_array==true → flach; sonst { wine: ... }
        $envelope = $_array ? 'none' : 'wine';

        return $this->getWine($prodID, [
            'withGrapes'   => 'simple', // Alt: reduzierte Grapes
            'withProducer' => true,
            'withRegions'  => true,
            'withSuper'    => false,    // Alt-get_Wine brauchte superIDs nicht
            'withCalc'     => true,
            'onlineFilter' => -1,
            'envelope'     => $envelope,
            'grapeOrder'   => 'ASC',    // Alt-get_Wine hatte ASC
            'withBioBreadcrumb' => false,
        ]);
    }

    /** Kompakte Liste von Produkten, optional nach IDs/where */
    public function get_just_Wines(?array $ids = null): array
    {
        $b = $this->builder('wr_product');
        $b->select('wr_product.*, wr_product.ID as prodID');

        if ($ids && \is_array($ids) && \count($ids) > 0) {
            $b->whereIn('wr_product.ID', array_map('intval', $ids));
        }

        $rows = $b->get()->getResultArray();
        $out = ['product' => [], 'no_stock' => []];
        foreach ($rows as $r) {
            $pid = (int)$r['prodID'];
            $out['product'][$pid]  = $r;
            $out['no_stock'][$pid] = (int)$r['stock_act'] - (int)$r['invoiced'];
        }
        return $out;
    }

    public function get_just_Wines_where(?array $ids = null, ?array $where = null): array
    {
        $b = $this->builder('wr_product');
        $b->select('wr_product.*, wr_product.ID as prodID');

        if ($where)   $b->where($where);
        if ($ids && \is_array($ids) && \count($ids) > 0) {
            $b->whereIn('wr_product.ID', array_map('intval', $ids));
        }

        $rows = $b->get()->getResultArray();
        $out = ['product' => [], 'no_stock' => []];
        foreach ($rows as $r) {
            $pid = (int)$r['prodID'];
            $out['product'][$pid]  = $r;
            $out['no_stock'][$pid] = (int)$r['stock_act'] - (int)$r['invoiced'];
        }
        return $out;
    }

    /**
     * Schlanke Produktliste mit optionalen Filtern/Pagination
     * (ersetzt dein get_just_Wines_array; Sort & Page bleiben)
     */
    public function get_just_Wines_array(
        ?array $ids      = null,
        ?array $where    = null,
        ?int   $_limit   = null,
        ?int   $_stock   = null,
        ?string $_orderBy= null,
        ?array $_grape   = null,
        ?int   $_purchase= null,
        ?int   $_online  = 1,
        ?int   $_page    = null,
        ?int   $_sale    = null
    ): array {
        $b = $this->builder('wr_product');
        $b->select('wr_product.*, wr_product.ID as prodID, wr_product.producer_ID, wr_prod_producer.producer as producer_name, wr_prod_producer.producer_uri')
          ->join('wr_prod_producer', 'wr_prod_producer.ID = wr_product.producer_ID', 'left');

        if ($_sale !== null) {
            $b->where('wr_product.aktions_preis >', 0);
        }

        if ($ids)   $b->whereIn('wr_product.ID', array_map('intval', $ids));
        if ($where) $b->where($where);

        $this->applyVisibilityFilters($b, $_stock, $_online);

        // Traubenfilter
        if (isset($_grape['ID']) && is_numeric($_grape['ID'])) {
            $b->join('wr_grapes2wine', 'wr_grapes2wine.product_ID = wr_product.ID', 'left')
              ->where('wr_grapes2wine.grape_ID', (int)$_grape['ID'])
              ->where('wr_grapes2wine.percent >=', (float)($_grape['min'] ?? 0))
              ->where('wr_grapes2wine.percent <=', (float)($_grape['max'] ?? 100));
        }

        // Paginierung
        if ($_page !== null && $_limit !== null) {
            $offset = max(0, ($_page - 1) * $_limit);
            $b->limit((int)$_limit, $offset);
        } elseif ($_limit !== null) {
            $b->limit((int)$_limit);
        }

        $this->safeOrderBy($b, $_orderBy);

        $rows = $b->get()->getResultArray();
        if (!$rows) return [];

        $ids  = array_column($rows, 'prodID');
        $gr   = $this->mapGrapesForProducts($ids);
        $tva  = $this->getTvaTable();

        $data = ['wines' => []];
        foreach ($rows as $r) {
            $pid = (int)$r['prodID'];
            $r['producer']            = $r['producer_name'] ?? '';
            $r['cont2prod_identifer'] = $r['producer_uri'] ?? '';
            $this->enrichBasicCalc($r, $tva);
            if (isset($gr[$pid])) $r['Grapes'] = $gr[$pid];
            $data['wines'][$pid] = $r;
        }
        return $data;
    }

    /** Für Warenkorb */
    public function get_BasketWine(int $prodID): array
    {
        $b = $this->builder('wr_product');
        $b->select('wr_product.*, wr_product.ID as prodID, wr_prod_producer.producer as producer, wr_prod_producer.ID as producerID')
          ->where('wr_product.ID', (int)$prodID)
          ->join('wr_prod_producer', 'wr_prod_producer.ID = wr_product.producer_ID', 'left');

        $row = $b->get()->getRowArray();
        if (!$row) return [];
        $stock_avail = (int)$row['stock_act'] - (int)$row['invoiced'];
        return ['wine' => $row, 'stock_avail' => $stock_avail];
    }

    /** Weine nach Traube */
    public function get_Wines_byGrape(?int $_grapeID = null, ?int $_limit = null, ?int $_stock = null): array
    {
        $b = $this->builder('wr_grapes2wine');
        $b->select('wr_product.*, wr_product.ID as prodID, wr_grapes2wine.percent')
          ->join('wr_product', 'wr_product.ID = wr_grapes2wine.product_ID')
          ->where('wr_product.online', 1);

        if ($_grapeID) $b->where('wr_grapes2wine.grape_ID', (int)$_grapeID);
        if ($_limit)   $b->limit((int)$_limit);
        if ($_stock === 1) $b->where($this->stockAvailExpr().' >', 0, false);

        $b->orderBy($this->stockAvailExpr(), 'DESC', false);

        $rows = $b->get()->getResultArray();
        $out  = ['wines' => []];
        foreach ($rows as $r) {
            $pid = (int)$r['prodID'];
            $r['no_stock'] = (int)$r['stock_act'] - (int)$r['invoiced'];
            $out['wines'][$pid] = $r;
        }
        return $out;
    }

    /** IDs-Liste (neueste, online, stock_avail>0) */
    public function get_Latest_IDs(?int $_limit = null): array
    {
        $b = $this->builder('wr_product');
        $b->select('*')
          ->where($this->stockAvailExpr().' >', 0, false)
          ->where('online', 1)
          ->orderBy('ID', 'DESC');
        if ($_limit !== null) $b->limit((int)$_limit);

        $out = [];
        foreach ($b->get()->getResultArray() as $r) {
            $out[(int)$r['ID']] = $r;
        }
        return $out;
    }

    /** Mehrere IDs → detaillierter */
    public function get_Wines_Array(array $ids): array
    {
        if (empty($ids)) return [];
        $b = $this->builder('wr_product');
        $b->select('wr_product.*, wr_product.ID as prodID, wr_prod_producer.producer')
          ->whereIn('wr_product.ID', array_map('intval', $ids))
          ->join('wr_prodOrdLev_1', 'wr_prodOrdLev_1.ID = wr_product.pol1_ID', 'left')
          ->join('wr_prodOrdLev_2', 'wr_prodOrdLev_2.ID = wr_product.pol2_ID', 'left')
          ->join('wr_prodOrdLev_3', 'wr_prodOrdLev_3.ID = wr_product.pol3_ID', 'left')
          ->join('wr_prod_producer', 'wr_prod_producer.ID = wr_product.producer_ID', 'left')
          ->orderBy('wr_product.name_pol1', 'ASC')
          ->orderBy('wr_product.name_pol2', 'ASC')
          ->orderBy('wr_product.name_pol3', 'ASC')
          ->orderBy('wr_prod_producer.producer', 'ASC')
          ->orderBy('wr_product.prod_name', 'ASC');

        $rows = $b->get()->getResultArray();
        $out = ['wines' => []];
        foreach ($rows as $r) {
            $pid = (int)$r['prodID'];
            $r['no_stock'] = (int)$r['stock_act'] - (int)$r['invoiced'];
            $out['wines'][$pid] = $r;
        }
        return $out;
    }

    /** Typen-Mapping (wie gehabt) */
    public function get_types(): array
    {
        return [
            'RF'  => 'Rot, fruchtig',
            'RS'  => 'Rot, seidig',
            'RK'  => 'Rot, kräftig',
            'RT'  => 'Rot, tanninreich',
            'RO'  => 'Rosé',
            'WF'  => 'Weiß, jung & fruchtig',
            'WV'  => 'Weiß, volumenreich',
            'WA'  => 'Weiß, Aromasorte',
            'WE'  => 'Weiß, edelsüß',
            'S'   => 'Sekt',
            'FR'  => 'Frizzante, Spumante',
            'CH'  => 'Champagner',
            'CHR' => 'Champagner Rosé',
            'CR'  => 'Crémant',
            'CRR' => 'Crémant Rosé',
        ];
    }

    // ----------------------------------------
    // Menü-/Navigations-Methoden (schlank)
    // ----------------------------------------

    /** Head-Menu Daten (Land/Region) – leichtgewichtige Zählung */
    public function get_head_menue(?string $_whereType = null, ?array $_wherePol1 = null): array
    {
        $data = [];

        // Level 1
        $b1 = $this->builder('wr_prodOrdLev_1')->select('*')->where('show', 1)->orderBy('pos', 'ASC');
        $pol1 = $b1->get()->getResultArray();
        if ($pol1) {
            $data['pol1'] = [];
            foreach ($pol1 as $r) {
                $data['pol1'][$r['ID']] = $r + ['noProdStock' => 0, 'noProdActive' => 0];
            }

            // Zählungen pro pol1 via Aggregation
            $b = $this->builder('wr_product')
                    ->select('pol1_ID, COUNT(*) as cnt, SUM(CASE WHEN (stock_act - invoiced) > 1 THEN 1 ELSE 0 END) as cnt_stock')
                    ->where('online', 1)
                    ->groupBy('pol1_ID');

            if ($_whereType) {
                if ($_whereType === 'men_Weiss') {
                    $b->groupStart()->whereIn('type', ['WF','WV','WA','WE'])->groupEnd();
                } elseif ($_whereType === 'men_Rot') {
                    $b->whereIn('type', ['RF','RS','RK','RT']);
                } elseif ($_whereType === 'men_Rose') {
                    $b->where('type', 'RO');
                } elseif ($_whereType === 'men_Cremant') {
                    $b->where('type', 'S');
                }
            }

            $counts = $b->get()->getResultArray();
            foreach ($counts as $c) {
                $id = (int)$c['pol1_ID'];
                if (isset($data['pol1'][$id])) {
                    $data['pol1'][$id]['noProdActive'] = (int)$c['cnt'];
                    $data['pol1'][$id]['noProdStock']  = (int)$c['cnt_stock'];
                }
            }
        }

        // Level 2 (gefiltert auf show=1)
        $b2 = $this->builder('wr_prodOrdLev_2')
            ->select('wr_prodOrdLev_2.ID, wr_prodOrdLev_2.pol1_ID, name_pol2, name_pol2_url, pos_pol2_menue, name_pol2_menue')
            ->join('wr_prodOrdLev_1', 'wr_prodOrdLev_1.ID = wr_prodOrdLev_2.pol1_ID')
            ->where('wr_prodOrdLev_2.show', 1)
            ->orderBy('pol1_ID', 'ASC')
            ->orderBy('pos_pol2_menue', 'DESC')
            ->orderBy('name_pol2', 'ASC');

        $pol2 = $b2->get()->getResultArray();
        if ($pol2) {
            $data['pol2'] = [];
            foreach ($pol2 as $r) $data['pol2'][$r['ID']] = $r;

            // Zählung pro pol2
            $b = $this->builder('wr_product')
                    ->select('pol2_ID, COUNT(*) as cnt, SUM(CASE WHEN (stock_act - invoiced) > 1 THEN 1 ELSE 0 END) as cnt_stock')
                    ->where('online', 1)
                    ->groupBy('pol2_ID');

            if ($_whereType) {
                if ($_whereType === 'men_Weiss') { $b->whereIn('type', ['WF','WV','WA','WE']); }
                elseif ($_whereType === 'men_Rot') { $b->whereIn('type', ['RF','RS','RK','RT']); }
                elseif ($_whereType === 'men_Rose') { $b->where('type', 'RO'); }
                elseif ($_whereType === 'men_Cremant') { $b->where('type', 'S'); }
            }

            $counts = $b->get()->getResultArray();
            foreach ($counts as $c) {
                $id = (int)$c['pol2_ID'];
                if (isset($data['pol2'][$id])) {
                    $data['pol2'][$id]['noProdActive'] = (int)$c['cnt'];
                    $data['pol2'][$id]['noProdStock']  = (int)$c['cnt_stock'];
                }
            }
        }

        return $data;
    }

    /** Kompakt: Menü (URI) – nur Struktur, keine teuren Zählungen */
    public function get_menue_uri($_wherePrice = null, $_whereType = null, $_wherePol1 = null, $_wherePol2 = null, $_wherePol3 = null, $whereProducer = null): array
    {
        $data = [];
        $data['pol1'] = $this->builder('wr_prodOrdLev_1')->select('*')->where('show', 1)->orderBy('pos', 'ASC')->get()->getResultArray();
        $data['pol2'] = $this->builder('wr_prodOrdLev_2')
            ->select('pol1_ID, wr_prodOrdLev_2.ID, name_pol2, name_pol2_url, pos_pol2_menue, name_pol2_menue')
            ->join('wr_prodOrdLev_1', 'wr_prodOrdLev_1.ID = wr_prodOrdLev_2.pol1_ID')
            ->where('wr_prodOrdLev_2.show', 1)
            ->orderBy('pol1_ID', 'ASC')
            ->orderBy('pos_pol2_menue', 'DESC')
            ->orderBy('name_pol2', 'ASC')
            ->get()->getResultArray();

        if ($_wherePol2) {
            $b3 = $this->builder('wr_prodOrdLev_3')->select('*')->orderBy('pos_pol3_menue', 'ASC')->orderBy('name_pol3', 'ASC')->where($_wherePol2);
            $data['pol3'] = $b3->get()->getResultArray();
        }
        return $data;
    }

    /** Vollmenü mit Zählungen (vereinfacht & sicher) */
    public function get_menue($_wherePrice = null, $_whereType = null, $_wherePol1 = null, $_wherePol2 = null, $_wherePol3 = null, $whereProducer = null): array
    {
        $data = [];

        // PRICE
        $priceCrit = $this->builder('wr_product_crit')->select('*')->where('cat', 'price')->orderBy('pos', 'ASC')->get()->getResultArray();
        foreach ($priceCrit as $p) {
            $data['price'][$p['ID']] = $p + ['noProdStock' => 0, 'noProdActive' => 0];
            $b = $this->builder()->select('ID, stock_act, invoiced')
                                 ->where('online', 1)
                                 ->where('price >=', (float)$p['from'])
                                 ->where('price <',  (float)$p['to']);
            if ($_whereType) { $b->where('type', $_whereType); }
            if ($_wherePol1) { $b->where($_wherePol1); }
            if ($_wherePol2) { $b->where($_wherePol2); }
            if ($_wherePol3) { $b->where($_wherePol3); }

            $cnt = 0; $cntStock = 0;
            foreach ($b->get()->getResultArray() as $r) {
                $cnt++;
                if (((int)$r['stock_act'] - (int)$r['invoiced']) > 1) $cntStock++;
            }
            $data['price'][$p['ID']]['noProdActive'] = $cnt;
            $data['price'][$p['ID']]['noProdStock']  = $cntStock;
        }

        // TYPE
        $typeCrit = $this->builder('wr_product_crit')->select('*')->where('cat', 'type')->orderBy('pos', 'ASC')->get()->getResultArray();
        foreach ($typeCrit as $t) {
            $data['type'][$t['ID']] = $t + ['noProdStock' => 0, 'noProdActive' => 0];
            $b = $this->builder()->select('ID, stock_act, invoiced')
                                 ->where('online', 1)
                                 ->where('type', $t['name']);
            if ($_wherePrice) {
                $b->where('price >=', (float)$_wherePrice['from'])
                  ->where('price <',  (float)$_wherePrice['to']);
            }
            if ($_wherePol1) $b->where($_wherePol1);
            if ($_wherePol2) $b->where($_wherePol2);
            if ($_wherePol3) $b->where($_wherePol3);

            $cnt = 0; $cntStock = 0;
            foreach ($b->get()->getResultArray() as $r) {
                $cnt++;
                if (((int)$r['stock_act'] - (int)$r['invoiced']) > 1) $cntStock++;
            }
            $data['type'][$t['ID']]['noProdActive'] = $cnt;
            $data['type'][$t['ID']]['noProdStock']  = $cntStock;
        }

        // POL1
        $pol1 = $this->builder('wr_prodOrdLev_1')->select('*')->where('show', 1)->orderBy('pos', 'ASC')->get()->getResultArray();
        foreach ($pol1 as $r) {
            $data['pol1'][$r['ID']] = $r + ['noProdStock' => 0, 'noProdActive' => 0];
            $b = $this->builder()->select('ID, stock_act, invoiced')
                                 ->where('online', 1)
                                 ->where('pol1_ID', (int)$r['ID']);
            if ($_whereType)  $b->where('type', $_whereType);
            if ($_wherePrice) { $b->where('price >=', (float)$_wherePrice['from'])->where('price <', (float)$_wherePrice['to']); }

            $cnt = 0; $cntStock = 0;
            foreach ($b->get()->getResultArray() as $x) {
                $cnt++;
                if (((int)$x['stock_act'] - (int)$x['invoiced']) > 1) $cntStock++;
            }
            $data['pol1'][$r['ID']]['noProdActive'] = $cnt;
            $data['pol1'][$r['ID']]['noProdStock']  = $cntStock;
        }

        // POL2
        $pol2 = $this->builder('wr_prodOrdLev_2')
            ->select('pol1_ID, wr_prodOrdLev_2.ID, name_pol2, name_pol2_url, pos_pol2_menue, name_pol2_menue')
            ->join('wr_prodOrdLev_1', 'wr_prodOrdLev_1.ID = wr_prodOrdLev_2.pol1_ID')
            ->where('wr_prodOrdLev_2.show', 1)
            ->orderBy('pol1_ID', 'ASC')
            ->orderBy('pos_pol2_menue', 'DESC')
            ->orderBy('name_pol2', 'ASC')
            ->get()->getResultArray();

        foreach ($pol2 as $r) {
            $id = (int)$r['ID'];
            $data['pol2'][$id] = $r + ['noProdStock' => 0, 'noProdActive' => 0];

            $b = $this->builder()->select('ID, stock_act, invoiced')
                                 ->where('online', 1)
                                 ->where('pol2_ID', $id);
            if ($_whereType)  $b->where('type', $_whereType);
            if ($_wherePrice) { $b->where('price >=', (float)$_wherePrice['from'])->where('price <', (float)$_wherePrice['to']); }

            $cnt = 0; $cntStock = 0;
            foreach ($b->get()->getResultArray() as $x) {
                $cnt++;
                if (((int)$x['stock_act'] - (int)$x['invoiced']) > 1) $cntStock++;
            }
            $data['pol2'][$id]['noProdActive'] = $cnt;
            $data['pol2'][$id]['noProdStock']  = $cntStock;
        }

        // POL3 (optional per Filter)
        if ($_wherePol2) {
            $pol3 = $this->builder('wr_prodOrdLev_3')->select('*')->where($_wherePol2)->orderBy('pos_pol3_menue', 'ASC')->orderBy('name_pol3', 'ASC')->get()->getResultArray();
            foreach ($pol3 as $r) {
                $id = (int)$r['ID'];
                $data['pol3'][$id] = $r + ['noProdStock' => 0, 'noProdActive' => 0];

                $b = $this->builder()->select('ID, stock_act, invoiced')
                                     ->where('online', 1)
                                     ->where('pol3_ID', $id);
                if ($_whereType)  $b->where('type', $_whereType);
                if ($_wherePrice) { $b->where('price >=', (float)$_wherePrice['from'])->where('price <', (float)$_wherePrice['to']); }

                $cnt = 0; $cntStock = 0;
                foreach ($b->get()->getResultArray() as $x) {
                    $cnt++;
                    if (((int)$x['stock_act'] - (int)$x['invoiced']) > 1) $cntStock++;
                }
                $data['pol3'][$id]['noProdActive'] = $cnt;
                $data['pol3'][$id]['noProdStock']  = $cntStock;
            }
        }

        return $data;
    }

    // ----------------------------------------
    // AJAX-Endpunkte (read-only, sicher)
    // ----------------------------------------

    public function ajax_get_Wines(?array $where = null): array
    {
        $b = $this->builder('wr_product');
        $b->select('wr_product.*, wr_product.ID as prodID, wr_product.identifer, wr_product.prod_name, wr_product.year,
                    wr_prod_producer.producer AS producer_name, wr_prod_producer.producer_uri')
          ->join('wr_prod_producer', 'wr_prod_producer.ID = wr_product.producer_ID', 'left');

        // online ODER zawe-online == 1
        $colZawe = $this->db->protectIdentifiers('wr_product.zawe-online');
        $b->groupStart()
              ->where('wr_product.online', 1)
              ->orWhere("$colZawe", 1)
          ->groupEnd();

        if ($where) $b->where($where);

        $b->orderBy('wr_product.name_pol1', 'ASC')
          ->orderBy('wr_product.name_pol2', 'ASC')
          ->orderBy('wr_product.name_pol3', 'ASC')
          ->orderBy('wr_product.producer_ID', 'ASC')
          ->orderBy('wr_product.prod_name', 'ASC');

        $rows = $b->get()->getResultArray();

        $out = ['wines' => [], 'rows' => []];
        foreach ($rows as $r) {
            $pid = (int)$r['prodID'];
            $out['wines'][$pid] = $r;

            $display = ($r['producer_name'] ?? '') . ': ' . ($r['prod_name'] ?? '') . ' - ' . ($r['year'] ?? '');
            $comp    = mb_strtolower($display);
            $comp    = strtr($comp, ['Á'=>'Ä','â'=>'a','á'=>'a','à'=>'a','é'=>'e','è'=>'e','ô'=>'o']); // grob normalisiert

            $out['rows'][] = [
                'identifer'      => $r['identifer'] ?? '',
                'prod_name'      => $r['prod_name'] ?? '',
                'producer'       => $r['producer_name'] ?? '',
                'identifer_prod' => $r['producer_uri'] ?? '',
                'year'           => $r['year'] ?? '',
                'value'          => $display,
                'compValue'      => $comp,
                'ID'             => $pid,
            ];
        }
        return $out;
    }

    public function ajax_get_Wineries(?array $where = null): array
    {
        $b = $this->builder('wr_prod_producer')->select('*')->where('show_prod !=', -1);
        if ($where) $b->where($where);
        $b->orderBy('producer', 'ASC');

        $rows = $b->get()->getResultArray();
        $out = [];
        foreach ($rows as $r) {
            $val  = $r['producer'];
            $comp = mb_strtolower($val);
            $comp = strtr($comp, ['Á'=>'Ä','â'=>'a','á'=>'a','à'=>'a','é'=>'e','è'=>'e','ô'=>'o']);
            $out[] = [
                'producer'  => $val,
                'value'     => $val,
                'compValue' => $comp,
                'ID'        => (int)$r['ID'],
            ];
        }
        return $out;
    }

    public function ajax_get_WineSmall(int $_id): array
    {
        $b = $this->builder('wr_product');
        $b->select('wr_product.*, wr_product.ID as prodID, wr_prod_producer.producer')
          ->where('wr_product.ID', (int)$_id)
          ->join('wr_prod_producer', 'wr_prod_producer.ID = wr_product.producer_ID', 'left');

        $row = $b->get()->getRowArray();
        if (!$row) return [];
        return [$row]; // alter Endpunkt gab JSON-Array mit einem Element zurück
    }

    // ----------------------------------------
    // Filter aus Session sicher bauen (ersetzt filter_where)
    // ----------------------------------------

    public function filter_where(): array
    {
        $w = [];
        $grape = ['ID' => null, 'min' => null, 'max' => null];

        $sess = $_SESSION['globalFilter'] ?? [];

        // Typ
        if (!empty($sess['typ']['show']) && !empty($sess['typ']['ID'])) {
            $typ = $sess['typ']['ID'];
            $sparkling = ['CH','CHR','CR','CRR','PR'];
            if (in_array($typ, $sparkling, true)) {
                if ($typ === 'CH') {
                    $w['wr_product.type IN'] = ['CH','CHR'];
                } elseif ($typ === 'CR') {
                    $w['wr_product.type IN'] = ['S','CR','CRR'];
                } elseif ($typ === 'PR') {
                    $w['wr_product.type'] = 'FR';
                } elseif ($typ === 'SR') {
                    $w['wr_product.type IN'] = ['SR','CHR','CRR'];
                }
            } else {
                $w['wr_product.type'] = $typ;
            }
        }

        // Region
        if (!empty($sess['region']['show']) && !empty($sess['region']['ID'])) {
            $w['wr_product.pol2_ID'] = (int)$sess['region']['ID'];
        }

        // Traube
        if (!empty($sess['traube']['show'])) {
            $grape['ID']  = (int)($sess['traube']['ID'] ?? 0);
            $grape['min'] = (float)($sess['traube']['min'] ?? 0);
            $grape['max'] = (float)($sess['traube']['max'] ?? 100);
        }

        // Alkohol
        if (!empty($sess['alkohol']['show'])) {
            $w['wr_product.al >='] = (float)$sess['alkohol']['min'];
            $w['wr_product.al <='] = (float)$sess['alkohol']['max'];
        }

        // Preis
        if (!empty($sess['preis']['show'])) {
            $min = (float)($sess['preis']['min'] ?? 0);
            $max = (float)($sess['preis']['max'] ?? 2000);
            if ($max < 2000) {
                $w['wr_product.price >='] = $min;
                $w['wr_product.price <='] = $max;
            } else {
                $w['wr_product.price >='] = $min;
            }
        }

        return ['where' => $w, 'grape' => $grape];
    }

    // ----------------------------------------
    // Mutations (sicher, mit Typcasts)
    // ----------------------------------------

    public function save_wine2email(int $product_ID): bool
    {
        return $this->builder()->set('anz_mail_2mon', 'anz_mail_2mon + 1', false)
                               ->where('ID', (int)$product_ID)
                               ->update();
    }

    public function save_stock(int $number, int $product_ID): bool
    {
        return $this->builder()->set('stock_act', (int)$number)
                               ->where('ID', (int)$product_ID)
                               ->update();
    }

    public function save_delivered(int $number, int $product_ID): bool
    {
        $this->db->transStart();
        $this->builder()->set('stock_act', 'stock_act - ' . (int)$number, false)
                        ->where('ID', (int)$product_ID)
                        ->update();
        $this->builder()->set('invoiced', 'invoiced - ' . (int)$number, false)
                        ->where('ID', (int)$product_ID)
                        ->update();
        $this->db->transComplete();
        return $this->db->transStatus();
    }

    public function save_invoiced(int $number, int $product_ID, $order = null): bool
    {
        return $this->builder()->set('invoiced', 'invoiced + ' . (int)$number, false)
                               ->where('ID', (int)$product_ID)
                               ->update();
    }

    // ----------------------------------------
    // Bildgrößen speichern (robuster)
    // ----------------------------------------

    public function save_img_size(): void
    {
        $pathBase = rtrim(FCPATH, '/\\') . '/bilder';
        $b = $this->builder('wr_product')->select('ID')->where('img_rel', null);
        $rows = $b->get()->getResultArray();
        foreach ($rows as $r) {
            $id = (int)$r['ID'];
            $path = $pathBase . "/{$id}_v.jpg";
            if (!is_file($path)) continue;

            try {
                $imageService = \Config\Services::image('imagick');
                $info = $imageService->withFile($path)->getFile()->getProperties(true);
                if (!empty($info['width']) && !empty($info['height']) && $info['width'] > 0) {
                    $ratio = (float)$info['height'] / (float)$info['width'];
                    $this->builder()->where('ID', $id)->set('img_rel', $ratio)->update();
                }
            } catch (\Throwable $e) {
                // Ignorieren / Loggen
                log_message('error', 'save_img_size error for ' . $id . ': ' . $e->getMessage());
            }
        }
    }

    // ----------------------------------------
    // Diverse Hilfen, die vorher inline waren
    // ----------------------------------------

    /** Minimal-Label-Anreicherung für Region/Producer */
    protected function attachRegionProducerLabels(array $row): array
    {
        // schon gejoint? Dann einfach Feldnamen angleichen
        if (isset($row['producer_name'])) {
            $row['producer'] = $row['producer_name'];
        }
        return $row;
    }

    // ----------------------------------------
    // (Optionale) Kompatibilitäts-Wrapper für Alt-Signaturen
    // ----------------------------------------

    /**
     * Alt: get_Wines(...) – hier schlank als Alias auf get_just_Wines_array
     */
    public function get_Wines($_where = null, $_limit = null, $_stock = null, $_orderBy = null, $_grape = null, $_purchase = null, $_online = null, $_page = null, $_bioz = null, $getProducer = null)
    {
        $where = [];
        if ($_where) $where = is_array($_where) ? $_where : [$this->db->escapeString((string)$_where) => null]; // best effort
        return $this->get_just_Wines_array(null, $where, $_limit, $_stock, $_orderBy, $_grape, $_purchase, $_online ?? 1, $_page, null);
    }

    public function get_Wines_single($_where = null): array
    {
        if ($_where === null) return [];
        $id  = (int)$_where;
        $w   = $this->get_Wine($id, true);
        return $w ? ['wines' => [$id => $w]] : [];
    }

    // === innerhalb der Klasse Wine_model hinzufügen ===

    /** Trauben inkl. Mapping-ID, grape_ID, cont_id – sortiert nach Percent */
    protected function mapGrapesForProductsFull(array $productIDs, string $order = 'DESC'): array
    {
        if (empty($productIDs)) return [];
        $order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';

        $b = $this->builder('wr_grapes2wine');
        $b->select('wr_grapes2wine.ID AS mapID, wr_grapes2wine.product_ID, wr_grapes2wine.grape_ID, wr_grapes2wine.percent,
                wr_grapes.grape, wr_grapes.cont_id')
          ->join('wr_grapes', 'wr_grapes.ID = wr_grapes2wine.grape_ID')
          ->whereIn('wr_grapes2wine.product_ID', array_map('intval', $productIDs))
          ->orderBy('wr_grapes2wine.product_ID', 'ASC')
          ->orderBy('wr_grapes2wine.percent', $order);

        $res = $b->get()->getResultArray();
        $out = [];
        foreach ($res as $r) {
            $pid = (int)$r['product_ID'];
            $gid = (int)$r['grape_ID'];
            $out[$pid][$gid] = [
                'percent'   => (float)$r['percent'],
                'grape'     => $r['grape'],
                'grape_ID'  => $gid,
                'ID'        => (int)$r['mapID'],
                'cont_id'   => isset($r['cont_id']) ? (int)$r['cont_id'] : null,
            ];
        }
        return $out;
    }

    /** Alle prodIDs einer super_id (neueste zuerst) */
    protected function getSuperIDs(?int $superID): array
    {
        if (!$superID) return [];
        $b = $this->builder('wr_product_super');
        $b->select('prodID')->where('superID', (int)$superID)->orderBy('prodID', 'DESC');
        return array_map('intval', array_column($b->get()->getResultArray(), 'prodID'));
    }

    /**
     * Master: Ein Wein – feldweise anreicherbar, kompatible Verpackung steuerbar.
     *
     * $opts:
     *  - withGrapes: 'full'|'simple'|false (default 'full')
     *  - withProducer: bool (default true)
     *  - withRegions: bool (default true)
     *  - withSuper: bool (default true)
     *  - withCalc: bool (default true)
     *  - onlineFilter: -1|0|1 (default -1 = ignorieren)
     *  - envelope: 'wine'|'wines'|'none' (default 'wine')
     *  - tvaFallback: float (default 19.0)
     *  - grapeOrder: 'DESC'|'ASC' (default 'DESC')
     */
    public function getWine(int $prodID, array $opts = []): array
    {
        $opts = array_merge([
            'withGrapes'   => 'full',
            'withProducer' => true,
            'withRegions'  => true,
            'withSuper'    => true,
            'withCalc'     => true,
            'onlineFilter' => -1,
            'envelope'     => 'wine',
            'tvaFallback'  => 19.0,
            'grapeOrder'   => 'DESC',
        ], $opts);

        $b = $this->builder('wr_product');
        // Basis-Select
        $select = ['wr_product.*', 'wr_product.ID AS prodID'];

        // Producer-Join
        if ($opts['withProducer']) {
            $b->join('wr_prod_producer', 'wr_prod_producer.ID = wr_product.producer_ID', 'left');
            $select[] = 'wr_prod_producer.ID AS producerID';
            $select[] = 'wr_prod_producer.producer AS producer_name';
            $select[] = 'wr_prod_producer.producer_uri';
        }
        // Regionen-Join
        if ($opts['withRegions']) {
            $b->join('wr_prodOrdLev_1', 'wr_prodOrdLev_1.ID = wr_product.pol1_ID', 'left');
            $b->join('wr_prodOrdLev_2', 'wr_prodOrdLev_2.ID = wr_product.pol2_ID', 'left');
            $b->join('wr_prodOrdLev_3', 'wr_prodOrdLev_3.ID = wr_product.pol3_ID', 'left');
            $select[] = 'wr_prodOrdLev_1.name_pol1, wr_prodOrdLev_1.name_pol1_url';
            $select[] = 'wr_prodOrdLev_2.name_pol2, wr_prodOrdLev_2.name_pol2_url';
            $select[] = 'wr_prodOrdLev_3.name_pol3, wr_prodOrdLev_3.name_pol3_url';
        }

        $b->select(implode(', ', $select))
          ->where('wr_product.ID', (int)$prodID);

        // Online-Filter (Admin ignoriert)
        if     ($opts['onlineFilter'] === 1) { $b->where('wr_product.online', 1); }
        elseif ($opts['onlineFilter'] === 0) { $b->where('wr_product.online', 0); }

        $row = $b->get()->getRowArray();
        if (!$row) {
            if     ($opts['envelope'] === 'wines') return ['wines' => []];
            elseif ($opts['envelope'] === 'wine')  return ['wine'  => []];
            return [];
        }

        // Kalkulationen
        if ($opts['withCalc']) {
            $tva = $this->getTvaTable() ?: [1 => (float)$opts['tvaFallback']];
            $this->enrichBasicCalc($row, $tva);
        }

        // Trauben
        if ($opts['withGrapes'] === 'full') {
            $g = $this->mapGrapesForProductsFull([(int)$prodID], (string)$opts['grapeOrder']);
            if (!empty($g[$prodID])) $row['Grapes'] = $g[$prodID];
        } elseif ($opts['withGrapes'] === 'simple') {
            $g = $this->mapGrapesForProducts([(int)$prodID]);
            if (!empty($g[$prodID])) $row['Grapes'] = $g[$prodID];
        }

        // superIDs
        if ($opts['withSuper']) {
            $row['superIDs'] = $this->getSuperIDs(isset($row['super_id']) ? (int)$row['super_id'] : null);
        }

        // Bio & Breadcrumb
        if (!empty($opts['withBioBreadcrumb'])) {
            // strukturierte Region statt HTML
            $row['region_path'] = $this->buildRegionPath($row);
            $row['region_breadcrumb_html'] =
                trim(implode(' / ', array_filter([$row['name_pol1'] ?? null, $row['name_pol2'] ?? null, $row['name_pol3'] ?? null])));

            // Prüfstellen-Namen (dedupliziert)
            $row['bio_labels'] = $this->getBioLabelsForProduct((int)$prodID);
        }

        // Producer-Aliase
        if ($opts['withProducer']) {
            if (isset($row['producer_name']))  $row['producer'] = $row['producer_name'];
            // producer_uri kommt bereits aus dem Select (falls vorhanden)
        }

        // Verpackung/Envelope
        if     ($opts['envelope'] === 'none')  return $row;
        elseif ($opts['envelope'] === 'wines') return ['wines' => [ (int)$row['prodID'] => $row ]];
        return ['wine' => $row];
    }

    /** Kompatibel zur alten Waren-Nutzung: flaches Array, alles angereichert */
    public function get_WineCI4(int $prodID): array
    {
        return $this->getWine($prodID, [
            'withGrapes'   => 'full',   // inkl. grape_ID, Mapping-ID, cont_id
            'withProducer' => true,     // producer + producer_uri
            'withRegions'  => true,     // name_pol1/2/3 (+ _url)
            'withSuper'    => true,     // superIDs
            'withCalc'     => true,     // no_stock/stock_avail, price_net, price_tva
            'onlineFilter' => -1,       // Admin: egal ob online oder nicht
            'envelope'     => 'none',   // ALT: flaches Array
            'grapeOrder'   => 'DESC',   // deine Präferenz
            'withBioBreadcrumb' => true,
        ]);
    }

    /** Region-Pfad aus bereits gejointen Feldern zusammenbauen (keine Extra-Queries) */
    protected function buildRegionPath(array $row): array
    {
        return [
            'pol1'         => $row['name_pol1']     ?? null,
            'pol1_url'     => $row['name_pol1_url'] ?? null,
            'pol2'         => $row['name_pol2']     ?? null,
            'pol2_url'     => $row['name_pol2_url'] ?? null,
            'pol3'         => $row['name_pol3']     ?? null,
            'pol3_url'     => $row['name_pol3_url'] ?? null,
            'producer'     => $row['producer_name'] ?? ($row['producer'] ?? null),
            'producer_uri' => $row['producer_uri']  ?? null,
        ];
    }

    /** Bio-Labels (Prüfstellen) für ein Produkt – nutzt deine bestehenden Models */
    protected function getBioLabelsForProduct(int $prodID): array
    {
        $labels = [];
        try {
            $ProductZ  = new \App\Models\Product_zert_model();
            $ProducerZ = new \App\Models\Producer_zert_model();

            // Mappings (mehrere möglich): Felder wie in Altcode (weiID, zertID)
            $maps = $ProductZ->get(['weiID' => $prodID]) ?? [];

            foreach ($maps as $m) {
                if (empty($m['zertID'])) continue;
                $pz  = $ProducerZ->get(['ID' => (int)$m['zertID']]) ?? [];
                $row = $pz[0] ?? null;
                if (!empty($row['pruefstelle'])) {
                    $labels[] = trim($row['pruefstelle']);
                }
            }
        } catch (\Throwable $e) {
            // stillschweigend
            log_message('debug', 'Bio label fetch failed for product '.$prodID.': '.$e->getMessage());
        }
        return array_values(array_unique(array_filter($labels)));
    }
}
