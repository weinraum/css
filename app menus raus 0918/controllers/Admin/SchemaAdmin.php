<?php namespace App\Controllers\Admin;

use CodeIgniter\Controller;

class SchemaAdmin extends Controller
{
    private function guard()
    {
        $allowEnvs = ['development','staging'];
        $okEnv = in_array(ENVIRONMENT, $allowEnvs, true);
        $token = $this->request->getGetPost('token');
        $okTok = $token && hash_equals((string)getenv('ADMIN_TOKEN') ?: '', (string)$token);
        if (!$okEnv && !$okTok) {
            return $this->response->setStatusCode(403)->setJSON(['ok'=>false,'err'=>'forbidden']);
        }
        return null;
    }

    public function wrContent()
    {
        if ($r = $this->guard()) return $r;

        $want = \Config\Schema::wrContent();
        $db   = db_connect('default'); // falls wr_content eigener Connection-Name hat: anpassen
        $schemaName = 'wr_content';

        $diff = new \App\Services\SchemaDiff($db);
        $sqls = $diff->diff($want, $schemaName);

        $apply = (bool)$this->request->getGetPost('apply'); // default: false (Dry-Run)
        $ran = [];
        if ($apply && $sqls) {
            foreach ($sqls as $stmt) {
                $db->query($stmt);
                $ran[] = $stmt;
            }
        }

        return $this->response->setJSON([
            'ok'   => true,
            'schema'=> $schemaName,
            'dryRun'=> !$apply,
            'plan' => $sqls,
            'ran'  => $ran,
        ]);
    }
}
