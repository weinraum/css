<?php declare(strict_types=1);

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;

class Aontroller extends Controller
{
    private const TTL_PAGE_DEFAULT     = 300;

    protected $cache;

    public function __construct()
    {
        $this->cache = service('cache');
    }

    /** Zentraler Renderer mit Layout-Slots und Flags */
   protected function render(string $view, array $data = [], array $opts = [])
{
    $cacheTtl = (int)($opts['cacheTtl'] ?? 0);
    $layout   = $opts['layout']   ?? 'layouts/default';

    // Page-Cache-Key (falls gewünscht)
    $cacheKey = null;
    if ($cacheTtl > 0) {
        $cacheKey = 'page:' . $view . ':' . md5(json_encode($data));
        $cached   = cache($cacheKey);
        if ($cached) {
            return $cached; // kompletter gerenderter HTML-String
        }
    }

    // View in Section einbetten
    $body = view($view, $data);
    $html = view($layout, $data + ['__content' => $body], ['saveData' => true]);

    // Section-Mechanik: default.php erwartet Section 'content'
    // -> eleganter via Sections:
    // $html = view($layout, $data, ['saveData'=>true]) . view($view, $data);

    // Wenn du Sections nutzen willst, ändere home-page-shop.php (s. unten)

    if ($cacheTtl > 0) {
        service('cache')->save($cacheKey, $html, $cacheTtl); // WICHTIG: save(), nicht set()
    }
    return $html;
}

    public function renderResponse(string $view, array $data = [], array $opts = []): ResponseInterface
    {
        return $this->response->setStatusCode((int)($opts['status'] ?? 200))
                              ->setBody($this->render($view, $data, $opts));
    }
}
