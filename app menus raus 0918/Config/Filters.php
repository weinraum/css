<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseFilters
{
    /**
     * Aliase für Filter-Klassen.
     *
     * @var array<string, class-string|list<class-string>>
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'cors'          => Cors::class,
        'forcehttps'    => ForceHTTPS::class,
        'pagecache'     => PageCache::class,
        'performance'   => PerformanceMetrics::class,

        // bestehend
        'abuse'        => \App\Filters\AbuseFilter::class,
        'statslogger'  => \App\Filters\StatsLogger::class,

        // NEU
        'authGuard'    => \App\Filters\AuthGuard::class,
        'cronGuard'    => \App\Filters\CronGuard::class,
    ];

    /**
     * Special required filters (laufen immer).
     *
     * @var array{before: list<string>, after: list<string>}
     */
    public array $required = [
        'before' => [
            // 'abuse',
            // 'forcehttps',
            // 'pagecache',
        ],
        'after' => [
            // 'pagecache',
            'performance',
            'toolbar',
        ],
    ];

    /**
     * Globale Filter (vor/nach jedem Request).
     *
     * @var array{
     *     before: array<string, array{except: list<string>|string}>|list<string>,
     *     after: array<string, array{except: list<string>|string}>|list<string>
     * }
     */
    public array $globals = [
        'before' => [
            // 'honeypot',
            'csrf',
            'abuse',
            // 'invalidchars',
        ],
        'after' => [
            'statslogger' => [
                'except' => [
                    'assets/*',
                    'api/*',
                    'favicon.ico',
                ],
            ],
        ],
    ];

    /**
     * Methodenspezifische Filter.
     *
     * @var array<string, list<string>>
     */
    public array $methods = [];

    /**
     * URI-gebundene Filter.
     *
     * @var array<string, array<string, list<string>>>
     */
    public array $filters = [
        // Variante A: Filter über Routen-Gruppen (wie vorgeschlagen) -> hier leer lassen.
        // Variante B (Alternative, falls du es lieber zentral willst):
        // 'authGuard' => ['before' => ['admin/*']],
        // 'cronGuard' => ['before' => ['cron_content*']],
        //
        // Wenn du CSRF für Admin-API via Header-Token NICHT mitsenden willst, kannst du CSRF für diese Pfade ausnehmen:
        // 'csrf' => ['except' => ['admin/cache/*']],
    ];
}
