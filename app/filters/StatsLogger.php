<?php namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

final class StatsLogger implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Nichts: wir loggen NACH dem Controller
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Minimale Guardrails: nur „echte“ Seiten (kein Redirect/Fehler, kein Asset)
        $status = $response->getStatusCode();
        if ($status >= 400) return;

        $uri = $request->getUri()->getPath();
        if ($uri === '' || str_starts_with($uri, 'assets/') || str_starts_with($uri, 'api/')) {
            return;
        }

        // TODO: hier dein Logging in stats.page_views aufrufen
        // (z. B. via Model/Service). Erstmal leer lassen, Hauptsache die Klasse existiert.
    }
}


