<?php namespace App\Services;

use Config\Menu as MenuConfig;

final class MenuCache
{
    public function __construct(private MenuConfig $cfg) {}

    // --- sichere Key-Builder: nur a–z0–9 + Unterstrich via md5 ---
    private function keyHtml(string $s, string $l, string $h): string
    { return 'wr_menu_html_' . md5($s.'|'.$l.'|'.$h); }

    private function keyTree(string $s, string $l, string $h): string
    { return 'wr_menu_tree_' . md5($s.'|'.$l.'|'.$h); }

    private function keyIndex(string $s, string $l): string
    { return 'wr_menu_idx_' . md5($s.'|'.$l); }

    private function keyLock(string $s, string $l): string
    { return 'wr_menu_lock_' . md5($s.'|'.$l); } // <-- KEINE ':' mehr

    // --- Locking ---
    public function tryLock(string $s, string $l): bool
    { return cache()->save($this->keyLock($s,$l), 1, $this->cfg->ttlLock); }

    public function unlock(string $s, string $l): void
    { cache()->delete($this->keyLock($s,$l)); }

    // --- Tree / Html ---
    public function getTree(string $s, string $l, string $h): ?array
    {
        $raw = cache()->get($this->keyTree($s,$l,$h));
        return is_array($raw) ? $raw : null;
    }

    public function saveTree(string $s, string $l, string $h, array $tree): void
    {
        cache()->save($this->keyTree($s,$l,$h), $tree, $this->cfg->ttlTree);
        $this->indexAdd($s,$l,$h);
    }

    public function getHtml(string $s, string $l, string $h): ?string
    {
        $raw = cache()->get($this->keyHtml($s,$l,$h));
        return is_string($raw) ? $raw : null;
    }

    public function saveHtml(string $s, string $l, string $h, string $html): void
    {
        cache()->save($this->keyHtml($s,$l,$h), $html, $this->cfg->ttlHtml);
        $this->indexAdd($s,$l,$h);
    }

    // --- Index-Management für gezieltes Invalidieren ---
    private function indexAdd(string $s, string $l, string $h): void
    {
        $k = $this->keyIndex($s,$l);
        $set = cache()->get($k);
        if (!is_array($set)) $set = [];
        $set[$h] = 1;
        cache()->save($k, $set, max($this->cfg->ttlTree, $this->cfg->ttlHtml));
    }

    private function indexAll(string $s, string $l): array
    {
        $k = $this->keyIndex($s,$l);
        $set = cache()->get($k);
        return is_array($set) ? array_keys($set) : [];
    }

    private function indexClear(string $s, string $l): void
    { cache()->delete($this->keyIndex($s,$l)); }

    public function invalidateSection(string $section, string $locale): void
    {
        // über Index alle Hashes löschen
        $hashes = $this->indexAll($section, $locale);
        foreach ($hashes as $h) {
            cache()->delete($this->keyTree($section,$locale,$h));
            cache()->delete($this->keyHtml($section,$locale,$h));
        }
        $this->indexClear($section, $locale);

        // Optional: nur verwenden, wenn dein Cache-Handler das wirklich unterstützt.
        // AUCH HIER KEINE ':' IM PATTERN.
        if (method_exists(cache(), 'deleteMatching')) {
            cache()->deleteMatching('wr_menu_'); // grober Prefix, falls implementiert
        }
    }
}
