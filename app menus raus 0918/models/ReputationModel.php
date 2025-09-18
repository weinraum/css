<?php namespace App\Models;

use CodeIgniter\Model;
use DateInterval;
use DateTimeImmutable;

class ReputationModel extends Model
{
        protected $DBGroup = 'security';     
        protected $table = 'reputation';
    protected $allowedFields = ['scope','key','score','status','until','updated_at'];

    public function fetchScopes(string $ip, ?string $rid): array
    {
        $keys = [];
        $keys[] = ['scope'=>'ip','key'=>inet_pton($ip)];
        $keys[] = ['scope'=>'ip_net','key'=>$this->toNet($ip)];
        if ($rid) $keys[] = ['scope'=>'cookie','key'=>substr($rid,0,64)];

        $builder = $this->builder();
        $builder->groupStart();
        foreach ($keys as $i => $k) {
            if ($i>0) $builder->orGroupStart();
            $builder->where('scope', $k['scope'])->where('key', $k['key']);
            if ($i>0) $builder->groupEnd();
        }
        $builder->groupEnd();
        return $builder->get()->getResultArray();
    }

    public function bumpScores(string $ip, ?string $rid, int $delta): int
    {
        $now = new DateTimeImmutable('now');
        $targets = [
            ['scope'=>'ip',     'key'=>inet_pton($ip)],
            ['scope'=>'ip_net', 'key'=>$this->toNet($ip)],
        ];
        if ($rid) $targets[] = ['scope'=>'cookie', 'key'=>substr($rid,0,64)];

        $max = 0;
        foreach ($targets as $t) {
            $row = $this->where($t)->get()->getRowArray();
            if ($row) {
                $new = (int)$row['score'] + $delta;
                $this->where('id', $row['id'])->set(['score'=>$new,'updated_at'=>$now->format('Y-m-d H:i:s')])->update();
                $max = max($max, $new);
            } else {
                $this->insert(array_merge($t, ['score'=>$delta,'status'=>'ok','until'=>null,'updated_at'=>$now->format('Y-m-d H:i:s')]));
                $max = max($max, $delta);
            }
        }
        return $max;
    }

    public function setBlock(string $ip, ?string $rid, string $status, int $minutes): void
    {
        $until = (new DateTimeImmutable('now'))->add(new DateInterval('PT'.$minutes.'M'))->format('Y-m-d H:i:s');
        foreach ($this->fetchScopes($ip, $rid) as $r) {
            $this->update($r['id'], ['status'=>$status,'until'=>$until,'updated_at'=>date('Y-m-d H:i:s')]);
        }
    }

    private function toNet(string $ip)
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $parts = explode('.', $ip); $parts[3] = '0'; return inet_pton(implode('.', $parts));
        }
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $bin = inet_pton($ip);
            return substr($bin, 0, 8).str_repeat("\x00", 8);
        }
        return null;
    }
}
