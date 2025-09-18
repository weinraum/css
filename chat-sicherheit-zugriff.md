# Security & Stats Setup (CodeIgniter 4)

## Zielbild
- **Security**: zentraler *AbuseFilter* (vor jedem Request) + *Reputation* (Scores/Bans) + *Incidents* (Logs).
- **Stats**: *StatsLogger* (nach jedem Request) für Page Views (und später Events).
- **Trennung**: eigene DB **security** (kurze Retention) und DB **stats** (Support/Analyse). Business-DBs bleiben unberührt.

## Komponenten & Ordnerstruktur
```
app/
  Config/
    Abuse.php              // Konfiguration für Filter/Reputation
    Filters.php            // Aliases + globals: 'abuse' before, 'statslogger' after
    Database.php           // DB groups: 'security', 'stats', 'default'
  Filters/
    AbuseFilter.php        // BEFORE-Filter (blockt)
    StatsLogger.php        // AFTER-Filter (loggt Views)
  Libraries/
    Abuse/
      CookieSigner.php     // signiertes RID-Cookie (secure dyn.)
      Detector.php         // Heuristiken/Regeln
      Reputation.php       // Statusabfrage + Score-Updates
  Models/
    IncidentModel.php      // $DBGroup='security' (incidents)
    ReputationModel.php    // $DBGroup='security' (reputation; scope/key)
    PageViewModel.php      // $DBGroup='stats'    (page_views)
```

## Config-Änderungen
### `app/Config/Filters.php`
- Aliases:
  - `'abuse' => \\App\\Filters\\AbuseFilter::class`
  - `'statslogger' => \\App\\Filters\\StatsLogger::class`
- Globals:
  - `before`: `csrf`, `abuse`
  - `after`: `statslogger` (mit `except: assets/*, api/*, favicon.ico`), `performance`, `toolbar`
- **Wichtig**: `except` nur in `globals['after']['statslogger']` nutzen, nicht in `$filters[...]`.

### `app/Config/Abuse.php`
- `$enabled = true`
- `$mode = 'monitor' | 'enforce'`
- `cookieName`, `secret`, Limits/Schwellen, verbotene Pfade etc.

### Cookies
- `CookieSigner::ensureRid(..., $secure = $request->isSecure())`
- Ohne SSL → `secure=false`, `SameSite=Lax`
- Mit SSL → `secure=true`, `SameSite=None`

---

## Datenbanken & Tabellen
### `security`
```sql
CREATE TABLE `incidents` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_bin` VARBINARY(16) NOT NULL,
  `cookie_rid` VARCHAR(64) NULL,
  `reason` VARCHAR(64) NOT NULL,
  `payload` TEXT NULL,
  `score` INT NOT NULL DEFAULT 0,
  INDEX (`created_at`), INDEX (`ip_bin`), INDEX (`cookie_rid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `reputation` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `scope` ENUM('ip','ip_net','cookie') NOT NULL,
  `key` VARBINARY(64) NOT NULL,
  `score` SMALLINT NOT NULL DEFAULT 0,
  `status` ENUM('ok','soft','blocked_short','blocked_long') NOT NULL DEFAULT 'ok',
  `until` DATETIME NULL,
  `updated_at` DATETIME NOT NULL,
  UNIQUE KEY `uniq_scope_key` (`scope`,`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### `stats`
```sql
CREATE TABLE `page_views` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `ts` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `path` VARCHAR(255) NOT NULL,
  `referrer` VARCHAR(255) NULL,
  `rid` VARCHAR(64) NULL,
  `cuid` BIGINT NULL,
  `ip_bin` VARBINARY(16) NULL,
  `ua_hash` BINARY(16) NULL,
  `is_bot` TINYINT(1) NOT NULL DEFAULT 0,
  KEY (`rid`,`ts`), KEY (`cuid`,`ts`), KEY (`path`,`ts`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## Typische Fehler & Fixes
- **Class not found** → Ordner-/Datei-Case + Namespace prüfen; OPcache resetten.
- **secure cookie over http** → `secure` dynamisch oder temporär global `false`.
- **Cache key reserved chars** → Key hashen (`md5`) oder nur erlaubte Zeichen.
- **trim(): array given** → `except` an falscher Stelle oder `trim()` auf Array.
- **Unknown column** → Models/Table-Schema angleichen.

---

## Pflege & Betrieb
- **Cronjobs** (per Webcron, wenn kein Shellzugang):
  - `abuse decay`: Scores senken, Bans freigeben
  - `security purge`: alte `incidents` löschen (>90 Tage)
  - `stats housekeeping`: alte `page_views` löschen (>24 Monate)
- **Modes**:  
  - `monitor`: loggt nur  
  - `enforce`: loggt + blockt (429/403)
- **Admin-View**: später kleine Übersicht mit Top-IPs, Scores, Gründen.

---

## Wann füllen sich die Tabellen?
- **`incidents`**: bei Treffern in den Abuse-Regeln oder wenn die Throttle anschlägt. Normale Aufrufe erzeugen nichts.
- **`reputation`**: wenn für IP/Cookie ein Score-Bump erfolgt (also bei Treffern). Ohne Treffer bleibt leer.
- **`page_views`**: wird von `StatsLogger` geschrieben – jeder echte Seitenaufruf.

---

## Tests
### 1. Verbotener Pfad
```
/.env
/wp-login.php
```
→ sollte Incident erzeugen.

### 2. SQL-Injection-Muster
```
/suche?q=%27%20OR%201=1--
```
→ sollte Incident + Score geben.

### 3. Throttle
```bash
for i in {1..40}; do curl -s -o /dev/null http://deinhost/ ; done
```
→ bei niedrigen Limits (z. B. 10 req / 60s) triggert Block + Incident.

---

## Debugging
- Vor Insert Log einbauen:
```php
log_message('debug', 'Abuse hit: {reason} ip={ip}', ['reason'=>$reason,'ip'=>$ip]);
```
- Wenn Log da, aber DB leer → Rechte/DBGroup prüfen.
- Wenn Log fehlt → Regel/Filter-Flow prüfen.
