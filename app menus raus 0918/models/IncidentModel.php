<?php namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\HTTP\RequestInterface;

class IncidentModel extends Model
{
            protected $DBGroup = 'security';     
protected $table      = 'incidents';
    protected $allowedFields = ['ts','path','method','param_keys','payload_snippet','ip_bin','ip_net','cookie_id','ua_hash','rule','score_delta'];

    public function logIncident(RequestInterface $req, string $ip, ?string $rid, array $hits): void
    {
        $paramKeys = implode(',', array_keys(array_merge($req->getGet()??[], $req->getPost()??[])));
        $ua = (string)$req->getUserAgent();

        $rows = [];
        foreach ($hits as $h) {
            $rows[] = [
                'ts' => date('Y-m-d H:i:s'),
                'path' => $req->getUri()->getPath(),
                'method' => $req->getMethod(),
                'param_keys' => substr($paramKeys,0,255),
                'payload_snippet' => substr((string)($h['snippet']??''), 0, 128),
                'ip_bin' => inet_pton($ip),
                'ip_net' => self::toNet($ip),
                'cookie_id' => $rid ? substr($rid, 0, 64) : null,
                'ua_hash' => substr(hash('md5', $ua, true), 0, 16),
                'rule' => $h['rule'],
                'score_delta' => (int)$h['score'],
            ];
        }
        if ($rows) $this->insertBatch($rows);
    }

    private static function toNet(string $ip)
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $parts = explode('.', $ip); $parts[3] = '0'; return inet_pton(implode('.', $parts));
        }
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            // /64: Null die unteren 64 Bits (grob)
            $bin = inet_pton($ip);
            return substr($bin, 0, 8).str_repeat("\x00", 8);
        }
        return null;
    }
}
