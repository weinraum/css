<?php namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\Abuse\Detector;
use App\Libraries\Abuse\Reputation;
use App\Libraries\Abuse\CookieSigner;
use Config\Services;

class AbuseFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $cfg = config('Abuse'); 
if (!$cfg || !$cfg->enabled) { return; }
        $resp = Services::response();

        // 1) Fingerprint
        $ip      = $request->getIPAddress();               // ggf. Proxy-Header validieren, wenn nötig
        $ua      = (string)($request->getUserAgent());
        $cookie  = $_COOKIE[$cfg->cookieName] ?? null;
$secure = $request->isSecure(); // aktuell false, später mit SSL true
$rid    = CookieSigner::ensureRid($cookie, $resp, $cfg->cookieName, $cfg->secret, $secure);

        // 2) Reputation-Lookup
        $rep = Reputation::instance();
        $status = $rep->currentStatus($ip, $rid);
if ($status->blocked) {
    return $resp->setStatusCode($status->code)->setBody('Access temporarily restricted.');
}

        // 3) Detection (kontextbezogene Regeln)
        $profile = Detector::routeProfile($request); // z. B. "public", "search", "login", "contact", "ajax"
        $hits    = Detector::analyze($request, $profile);  // Liste von RuleHits (rule, scoreDelta, snippet)

        // 4) Throttle als Signal (optional)
        if ($cfg->useThrottler) {
            $throttler = Services::throttler();
$safeKey = 'ip_' . md5($ip);
$ok = $throttler->check($safeKey, $cfg->throttleLimit, $cfg->throttleWindow);
            if (!$ok) {
                $hits[] = ['rule'=>'THROTTLE', 'score'=> $cfg->scores['throttle'], 'snippet'=>null];
            }
        }

        // 5) Reputation-Update + Reaktion
        if (!empty($hits)) {
            $decision = $rep->applyHits($ip, $rid, $request, $hits); // speichert Incidents, erhöht Score, setzt Status
            if ($decision->action === 'delay') {
                usleep($cfg->tarpitDelayMs * 1000);
            } elseif ($decision->action === '429') {
                return $resp->setStatusCode(429)->setBody('Too many requests.');
            } elseif ($decision->action === '403') {
                return $resp->setStatusCode(403)->setBody('Forbidden.');
            }
        }
        // → sonst durchlassen
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // nichts
    }
}
