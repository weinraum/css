<?php namespace App\Cells;

use CodeIgniter\View\Cells\Cell;

class ProductTeaserCell extends Cell
{
    // Sichere Defaults setzen – so gibt es kein "must not be accessed before initialization"
    public array  $weine          = [];
    public ?int   $prod_ID        = null;
    public ?array $linkIdentifers = null;
    public int    $_anzPack       = 0;
    public ?string $_edit         = null; // "edit" | null
    public int    $_lazy          = 1;    // 1 = lazy

    public function render(): string
    {
        $App = new \App\Libraries\App();

        // Eingaben absichern
        $weine  = is_array($this->weine) ? $this->weine : [];
        $wines  = $weine['wines'] ?? [];
        $prodID = (int) ($this->prod_ID ?? 0);

        // Produkt vorhanden?
        $w = ($prodID && isset($wines[$prodID]) && is_array($wines[$prodID])) ? $wines[$prodID] : null;
        if ($w === null) {
            // Kein Produkt -> nichts rendern (oder kleinen Platzhalter zurückgeben)
            return '';
        }

        // Routing-Basis wie bisher
        $_link = ($this->_edit === 'edit') ? 'wein_edit' : 'weine';

        // Typ-Link
        $_type = isset($w['type'])
            ? $App::getLinkfromBracket("[[typ || {$w['type']}]]", $this->linkIdentifers)
            : '';

        // Bild-Flags: bevorzugt DB-Flags, sonst file_exists() (Fallback)
        $has_fl_v    = isset($w['has_fl_v'])    ? (int) $w['has_fl_v']    : -1;
        $has_fl_v_xs = isset($w['has_fl_v_xs']) ? (int) $w['has_fl_v_xs'] : -1;
        $has_alt     = isset($w['has_alt'])     ? (int) $w['has_alt']     : -1;

        $baseAlt = defined('DOEMELLUNNCHE') ? DOEMELLUNNCHE : FCPATH;
        $p_fl_v     = $baseAlt . "flasche_klein/{$prodID}_v.webp";
        $p_fl_v_xs  = $baseAlt . "flasche_klein/{$prodID}_v_xs.webp";
        $p_alt      = $baseAlt . "alt/WeiID{$prodID}_254.jpg";

        if ($has_fl_v    === -1) { $has_fl_v    = file_exists($p_fl_v)    ? 1 : 0; }
        if ($has_fl_v_xs === -1) { $has_fl_v_xs = file_exists($p_fl_v_xs) ? 1 : 0; }
        if ($has_alt     === -1) { $has_alt     = file_exists($p_alt)     ? 1 : 0; }

        $picBase = defined('PICPATH') ? PICPATH : '/_/img/';
        $img = [
            'has_fl_v'       => $has_fl_v,
            'has_fl_v_xs'    => $has_fl_v_xs,
            'has_alt'        => $has_alt,
            'src_fl_v'       => $picBase . 'weine/flasche_klein/' . $prodID . '_v.jpg',
            'src_fl_v_webp'  => $picBase . 'weine/flasche_klein/' . $prodID . '_v_xs.webp',
            'src_alt'        => $picBase . 'weine/alt/WeiID' . $prodID . '_254.jpg',
            'placeholder'    => '/_/img/wein-folgt-xs.webp',
            'w'              => (int) ($w['img_w'] ?? 254),
            'h'              => (int) ($w['img_h'] ?? 254),
        ];

        // Warenkorb-Anzahl aus Session (failsafe)
        $s      = session();
        $prodIDKey = $w['prodID'] ?? $prodID;
        $inCart = (int) ($s->get('cart_art.' . $prodIDKey . '.number') ?? 0);

        // Variablen an View geben
        return view('cells/product_teaser', [
            'w'              => $w,
            'prod_ID'        => $prodID,
            '_link'          => $_link,
            '_type'          => $_type,
            'img'            => $img,
            'anzPack'        => (int) $this->_anzPack,
            'inCart'         => $inCart,
            '_lazy'          => (int) $this->_lazy,
            'linkIdentifers' => $this->linkIdentifers,
            'App'            => $App,
        ]);
    }
}
