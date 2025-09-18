<?php namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthGuard implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (is_cli()) return; // CLI nie blocken

        // 1) IP-Allowlist (optional)
        $allowlist = $this->csv(env('admin.allowlist', '')); // z.B. "127.0.0.1,::1,10.0.0.0/8"
        if ($allowlist && $this->ipAllowed($request->getIPAddress(), $allowlist)) {
            return; // IP whitelisted → durchlassen
        }

        // 2) Session-basierte Admin-Auth
        $session     = session();
        $sessionKey  = env('admin.sessionKey', 'isAdmin');
        $roleKey     = env('admin.roleKey', 'role');
        $allowed     = $this->csv(env('admin.allowedRoles', 'admin')); // z.B. "admin,editor"
        $hasAdmin    = (bool) $session->get($sessionKey);
        $role        = (string) ($session->get($roleKey) ?? 'admin');

        if ($hasAdmin && (empty($allowed) || in_array($role, $allowed, true))) {
            return;
        }

        // 3) Bearer-/Header-Token (für Admin-APIs)
        $apiToken = (string) env('admin.apiToken', '');
        if ($apiToken !== '') {
            $authHeader = $request->getHeaderLine('Authorization');
            $xHeader    = $request->getHeaderLine('X-Admin-Token');
            $token      = '';
            if (stripos($authHeader, 'Bearer ') === 0) {
                $token = trim(substr($authHeader, 7));
            } elseif ($xHeader !== '') {
                $token = trim($xHeader);
            }
            if ($token !== '' && hash_equals($apiToken, $token)) {
                return;
            }
        }

        // 4) Ablehnen (JSON bei API, sonst Redirect)
        if ($this->wantsJSON($request)) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON(['ok' => false, 'error' => 'Unauthorized']);
        }

        return redirect()->to('/logout'); // oder /login, je nach Projekt
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}

    // ---- helpers ----
    private function wantsJSON(RequestInterface $request): bool
    {
        if ($request->isAJAX()) return true;
        $accept = $request->getHeaderLine('Accept');
        if (stripos($accept, 'application/json') !== false) return true;
        $xr = strtolower($request->getHeaderLine('X-Requested-With'));
        return $xr === 'xmlhttprequest';
    }

    private function csv(string $s): array
    {
        $s = trim($s);
        if ($s === '') return [];
        return array_values(array_filter(array_map('trim', explode(',', $s))));
    }

    private function ipAllowed(string $ip, array $allowlist): bool
    {
        // simple: exakte IPs, CIDR optional (nur /32 & /24 üblich)
        foreach ($allowlist as $rule) {
            if ($rule === $ip) return true;
            if (strpos($rule, '/') !== false) {
                if ($this->ipInCidr($ip, $rule)) return true;
            }
        }
        return false;
    }

    private function ipInCidr(string $ip, string $cidr): bool
    {
        [$subnet, $mask] = explode('/', $cidr);
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false ||
            filter_var($subnet, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
            return false; // keep it simple: nur IPv4-CIDR hier
        }
        $ipLong     = ip2long($ip);
        $subnetLong = ip2long($subnet);
        $maskLong   = -1 << (32 - (int)$mask);
        $subnetBase = $subnetLong & $maskLong;
        return ($ipLong & $maskLong) === $subnetBase;
    }
}
