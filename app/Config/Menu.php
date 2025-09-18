<?php namespace Config;

use CodeIgniter\Config\BaseConfig;

class Menu extends BaseConfig
{
    public array $sections = ['main','wein','regionen','winzer'];
    public string $defaultLocale = 'de';

    public int $ttlTree  = 60 * 60 * 24 * 180; // 180 Tage
    public int $ttlHtml  = 60 * 60 * 24 * 180; // 180 Tage
    public int $ttlLock  = 10;                 // Sekunden für Build-Lock
}
