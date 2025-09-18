<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\ContentService;
use CodeIgniter\Exceptions\PageNotFoundException;

class ContentAdmin extends BaseController
{
    protected ContentService $content;

    public function __construct()
    {
        $this->content = service('contentService'); // registriere Service in Services.php oder use new ContentService()
    }

    /** Simple Guard (ersetzen durch eure ACL/Middleware) */
    protected function ensureAdmin(): void
    {
        // Beispiel: if (! session('is_admin')) throw PageNotFoundException::forPageNotFound();
        // Vorläufig: immer erlaubt (bitte austauschen)
    }

    // GET /admin/content
    public function index()
    {
        $this->ensureAdmin();

        $filters = [
            'q'         => trim($this->request->getGet('q') ?? ''),
            'category'  => $this->request->getGet('category'),
            'producer'  => $this->request->getGet('producer'),
            'status'    => $this->request->getGet('status'),
        ];

        $page     = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage  = min(100, max(10, (int) ($this->request->getGet('perPage') ?? 25)));

        [$items, $pager, $meta] = $this->content->listing($filters, $page, $perPage);

        return view('admin/content/index', [
            'items'     => $items,
            'pager'     => $pager,
            'meta'      => $meta,
            'filters'   => $filters,
        ]);
    }

    // GET /admin/content/create
    public function create()
    {
        $this->ensureAdmin();

        return view('admin/content/form', [
            'data'      => [
                'id'        => null,
                'title'     => '',
                'identifer' => '',
                'date'      => date('Y-m-d'),
                'onstart'   => 0,
                'status'    => 1,
                'is_zeitung' => 0,
                'is_provence' => 0,
                'is_traubenwerke' => 0,
                '_categories' => [],
                'producer_id'  => null,
                'slices' => [],
            ],
            'errors'    => [],
            'mode'      => 'create',
            'categories'=> $this->content->getAllCategories(),
            'producers' => $this->content->getAllProducers(),
        ]);
    }

    // GET /admin/content/edit/{id}
    public function edit(int $id)
    {
        $this->ensureAdmin();

        $data = $this->content->get($id);
        if (!$data) throw PageNotFoundException::forPageNotFound();

        return view('admin/content/form', [
            'data'       => $data,
            'errors'     => [],
            'mode'       => 'edit',
            'categories' => $this->content->getAllCategories(),
            'producers'  => $this->content->getAllProducers(),
        ]);
    }

    // POST /admin/content/save
    public function save()
    {
        $this->ensureAdmin();

        $post  = $this->request->getPost();
        $files = $this->request->getFiles();

        try {
            $id = $this->content->save($post, $files);
            return redirect()->to("/admin/content/edit/{$id}")
                ->with('success', 'Content gespeichert.');
        } catch (\CodeIgniter\Validation\ValidationException $ve) {
            $errors = $ve->getErrors();
        } catch (\Throwable $t) {
            log_message('error', 'Content save failed: {msg}', ['msg' => $t->getMessage()]);
            $errors = ['_general' => 'Speichern fehlgeschlagen. ' . $t->getMessage()];
        }

        // Bei Fehlern Formular erneut anzeigen
        $id    = isset($post['id']) && $post['id'] !== '' ? (int)$post['id'] : null;
        $data  = $id ? $this->content->get($id) : null;
        $fill  = $data ? array_merge($data, $post) : $post;

        return view('admin/content/form', [
            'data'       => $fill,
            'errors'     => $errors,
            'mode'       => $id ? 'edit' : 'create',
            'categories' => $this->content->getAllCategories(),
            'producers'  => $this->content->getAllProducers(),
        ]);
    }

    // POST /admin/content/delete/{id}
    public function delete(int $id)
    {
        $this->ensureAdmin();

        if ($this->request->getMethod() !== 'post' || ! $this->request->getPost('confirm')) {
            return redirect()->to("/admin/content/edit/{$id}")
                ->with('error', 'Löschen abgebrochen – Bestätigung fehlt.');
        }

        try {
            $this->content->delete($id);
            return redirect()->to('/admin/content')->with('success', 'Content gelöscht.');
        } catch (\Throwable $t) {
            log_message('error', 'Content delete failed: {msg}', ['msg' => $t->getMessage()]);
            return redirect()->to("/admin/content/edit/{$id}")
                ->with('error', 'Löschen fehlgeschlagen: ' . $t->getMessage());
        }
    }

    // Optional: POST /admin/content/upload/{id}
    public function upload(int $id)
    {
        $this->ensureAdmin();

        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(405);
        }

        try {
            $this->content->uploadImages($id, $this->request->getFiles());
            return $this->response->setJSON(['ok' => true]);
        } catch (\Throwable $t) {
            return $this->response->setJSON(['ok' => false, 'error' => $t->getMessage()])->setStatusCode(400);
        }
    }
}
