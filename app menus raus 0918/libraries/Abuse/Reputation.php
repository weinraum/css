<?php namespace App\Libraries\Abuse;

use App\Models\IncidentModel;
use App\Models\ReputationModel;
use CodeIgniter\HTTP\RequestInterface;
use Config\Abuse as AbuseCfg;
use DateTimeImmutable;

final class Reputation
{
    private static ?self $inst = null;
    private ReputationModel $repModel;
    private IncidentModel $incModel;
    private AbuseCfg $cfg;

    private function __construct()
    {
        $this->repModel = new ReputationModel();
        $this->incModel = new IncidentModel();
        $this->cfg      = config('Abuse');
    }

    public static function instance(): self
    {
        return self::$inst ??= new self();
    }

    public function currentStatus(string $ip, ?string $rid): object
    {
        $s = (object)['blocked'=>false,'code'=>200];
        $records = $this->repModel->fetchScopes($ip, $rid);
        $now = new DateTimeImmutable('now');
        foreach ($records as $r) {
            if (in_array($r['status'], ['blocked_short','blocked_long'], true) && new DateTimeImmutable($r['until']) > $now) {
                $s->blocked = true; $s->code = ($r['status']==='blocked_short'?429:403);
                return $s;
            }
        }
        return $s;
    }

    public function applyHits(string $ip, ?string $rid, RequestInterface $req, array $hits): object
    {
        $total = array_sum(array_column($hits,'score'));
        if ($total <= 0) return (object)['action'=>null];

        // 1) Incident schreiben (kompakt)
        $this->incModel->logIncident($req, $ip, $rid, $hits);

        // 2) Score erhöhen (auf scopes: cookie, ip, ip_net)
        $newScore = $this->repModel->bumpScores($ip, $rid, $total);

        // 3) Entscheidung
        if ($this->cfg->mode !== 'enforce') return (object)['action'=>null];

        if ($newScore >= $this->cfg->thresholdLong) {
            $this->repModel->setBlock($ip, $rid, 'blocked_long', $this->cfg->banLongMin);
            return (object)['action'=>'403'];
        }
        if ($newScore >= $this->cfg->thresholdShort) {
            $this->repModel->setBlock($ip, $rid, 'blocked_short', $this->cfg->banShortMin);
            return (object)['action'=>'429'];
        }
        if ($newScore >= $this->cfg->thresholdTarpit) {
            return (object)['action'=>'delay'];
        }
        return (object)['action'=>null];
    }
}


