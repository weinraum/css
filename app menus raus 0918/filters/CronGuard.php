<?php namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class CronGuard implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (is_cli()) return; // Cron via CLI immer ok

        // Optional: IP-Liste
        $allowlist = $this->csv(env('cron.allowlist', ''));
        $ipOk      = empty($allowlist) ? true : in_array($request->getIPAddress(), $allowlist, true);

        // Token per Header oder Query
        $needToken = (string) env('cron.token', '');
        $hdrToken  = trim($request->getHeaderLine('X-Cron-Token') ?? '');
        $qryToken  = trim((string) $request->getGet('token'));

        if ($ipOk && $needToken !== '' && (hash_equals($needToken, $hdrToken) || hash_equals($needToken, $qryToken))) {
            return;
        }

        // Wenn kein Token gesetzt ist, strikt blocken (oder -> 403)
        return service('response')->setStatusCode(403)->setBody('Forbidden');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}

    private function csv(string $s): array
    {
        $s = trim($s);
        if ($s === '') return [];
        return array_values(array_filter(array_map('trim', explode(',', $s))));
    }
}
