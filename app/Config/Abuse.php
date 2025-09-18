<?php namespace Config;

use CodeIgniter\Config\BaseConfig;

class Abuse extends BaseConfig
{
    public bool $enabled = true;
    public string $mode = 'enforce'; // vorher: 'monitor'

    public bool $useThrottler = true;
    public int  $throttleLimit  = 20;
    public int  $throttleWindow = 60;

    public string $cookieName = 'rid';
    public string $secret     = 'CHANGE_ME_SECRET'; // per .env überschreiben

    public int $tarpitDelayMs = 400; // 300–600 ms ok

    public array $scores = [
        'forbidden_path' => 10,
        'sql_keyword'    => 10,   // hoch → Sofortsperre via thresholdShort
        'danger_seq'     => 5,
        'payload_burst'  => 3,
        'throttle'       => 3,
    ];

    public int $thresholdTarpit = 5;
    public int $thresholdShort  = 10; // = sql_keyword
    public int $thresholdLong   = 20;

    public int $banShortMin = 30;
    public int $banLongMin  = 6 * 60;

    public int $decayEveryMin = 10;

    public array $profiles = [
        'public'  => ['forbidden_paths', 'sql_keywords_in_disallowed', 'payload_burst'],
        'search'  => ['forbidden_paths', 'sql_keywords_in_disallowed', 'payload_burst'],
        'login'   => ['forbidden_paths', 'sql_keywords_in_disallowed', 'payload_burst'],
        'contact' => ['forbidden_paths', 'sql_keywords_in_disallowed', 'payload_burst', 'danger_sequences'],
        'ajax'    => ['forbidden_paths', 'sql_keywords_in_disallowed', 'payload_burst', 'danger_sequences'],
    ];

    public array $forbiddenPaths = [
        '/.env','/wp-login.php','/xmlrpc.php','/vendor/','/.git','/phpinfo','/config.php'
    ];

    // NEU: zentrale Keyword-Liste (englisch, auf deiner deutschen Seite nie legitim)
    public array $sqlKeywords = [
        'select','insert','update','delete','drop',
        'into','outfile','load_file','xp_cmdshell',
        'sleep','benchmark','information_schema'
    ];
}
