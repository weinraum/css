<?php namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;

class MenuAdmin extends Controller
{
    private function guard(): ?ResponseInterface
    {
        // Minimaler Schutz: nur in development/staging oder via Token
        $allowEnvs = ['development','staging'];
        $okEnv = in_array(ENVIRONMENT, $allowEnvs, true);
        $token = $this->request->getGetPost('token');
        $okTok = $token && hash_equals((string)getenv('ADMIN_TOKEN') ?: '', (string)$token);
        if (!$okEnv && !$okTok) {
            return $this->response->setStatusCode(403)->setJSON(['ok'=>false,'err'=>'forbidden']);
        }
        return null;
    }

    public function invalidateAll()
    {
        if ($r = $this->guard()) return $r;
        $svc = service('menuService');
        foreach (['lexikon','regionen','regionen_content','winzer','wein','wein_winzer'] as $sec) {
            $svc->invalidateSection($sec, 'de');
        }
        return $this->response->setJSON(['ok'=>true,'action'=>'invalidateAll']);
    }

    public function warmupAll()
    {
        if ($r = $this->guard()) return $r;
        $svc = service('menuService');
        $ctx = ['navigation'=>null,'activeIdentifer'=>null];
        // Warmup: jeweils einmal rendern (Tree+HTML landen im Cache)
        foreach (['lexikon','regionen','regionen_content','winzer','wein','wein_winzer'] as $sec) {
            $svc->render($sec, 'de', $ctx);
        }
        return $this->response->setJSON(['ok'=>true,'action'=>'warmupAll']);
    }

    public function reindexAll()
    {
        if ($r = $this->guard()) return $r;
        $idx = new \App\Services\MenuIndexer(db_connect('default'));
        $cnt = [
            'lexikon' => $idx->reindexLexikon(),
            'regionen'=> $idx->reindexRegionen(),
            'winzer'  => $idx->reindexWinzer(),
        ];
        service('menuService')->invalidateSection('lexikon','de');
        service('menuService')->invalidateSection('regionen','de');
        service('menuService')->invalidateSection('regionen_content','de');
        service('menuService')->invalidateSection('winzer','de');
        return $this->response->setJSON(['ok'=>true,'action'=>'reindexAll','counts'=>$cnt]);
    }
}
