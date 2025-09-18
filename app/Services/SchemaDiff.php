<?php namespace App\Services;

use CodeIgniter\Database\BaseConnection;

final class SchemaDiff
{
    public function __construct(private BaseConnection $db) {}

    public function inspectTable(string $schema, string $table): array
    {
        // columns
        $cols = $this->db->query("
            SELECT COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE, COLUMN_DEFAULT, EXTRA, COLUMN_KEY
            FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?
            ORDER BY ORDINAL_POSITION
        ", [$schema, $table])->getResultArray();

        // constraints
        $idx = $this->db->query("
            SELECT INDEX_NAME, NON_UNIQUE, GROUP_CONCAT(COLUMN_NAME ORDER BY SEQ_IN_INDEX) AS COLS
            FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?
            GROUP BY INDEX_NAME, NON_UNIQUE
        ", [$schema, $table])->getResultArray();

        return ['columns'=>$cols, 'indexes'=>$idx];
    }

    public function diff(array $want, string $schema): array
    {
        $sql = [];
        foreach ($want as $table => $def) {
            $exists = (bool)$this->db->query("
                SELECT 1 FROM information_schema.TABLES
                WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?
            ", [$schema, $table])->getFirstRow();

            if (!$exists) {
                // CREATE TABLE
                $parts = [];
                foreach ($def['columns'] as $col => $ddl) {
                    $parts[] = "`{$col}` {$ddl}";
                }
                if (!empty($def['primary'])) {
                    $parts[] = "PRIMARY KEY (`".implode('`,`',$def['primary'])."`)";
                }
                if (!empty($def['unique'])) {
                    foreach ($def['unique'] as $name => $cols) {
                        $parts[] = "UNIQUE KEY `{$name}` (`".implode('`,`',$cols)."`)";
                    }
                }
                if (!empty($def['keys'])) {
                    foreach ($def['keys'] as $name => $cols) {
                        $parts[] = "KEY `{$name}` (`".implode('`,`',$cols)."`)";
                    }
                }
                $engine  = $def['engine']  ?? 'InnoDB';
                $charset = $def['charset'] ?? 'utf8mb4';
                $collate = $def['collate'] ?? 'utf8mb4_unicode_ci';
                $sql[] = "CREATE TABLE `{$schema}`.`{$table}` (\n  ".implode(",\n  ", $parts)."\n) ENGINE={$engine} DEFAULT CHARSET={$charset} COLLATE={$collate};";
                continue;
            }

            // ALTER TABLE: fehlende Spalten/Indizes (keine Destruktiv-Änderungen automatisch!)
            $have = $this->inspectTable($schema, $table);
            $haveCols = array_column($have['columns'],'COLUMN_NAME');
            $adds = [];
            foreach ($def['columns'] as $col => $ddl) {
                if (!in_array($col, $haveCols, true)) {
                    $adds[] = "ADD COLUMN `{$col}` {$ddl}";
                }
            }
            // Unique/Keys
            $haveIdx = [];
            foreach ($have['indexes'] as $i) {
                $haveIdx[$i['INDEX_NAME']] = explode(',', (string)$i['COLS']);
            }
            $addIdx = [];
            foreach (($def['unique'] ?? []) as $name => $cols) {
                if (!isset($haveIdx[$name])) $addIdx[] = "ADD UNIQUE KEY `{$name}` (`".implode('`,`',$cols)."`)";
            }
            foreach (($def['keys'] ?? []) as $name => $cols) {
                if (!isset($haveIdx[$name])) $addIdx[] = "ADD KEY `{$name}` (`".implode('`,`',$cols)."`)";
            }

            if ($adds || $addIdx) {
                $sql[] = "ALTER TABLE `{$schema}`.`{$table}`\n  ".implode(",\n  ", array_merge($adds,$addIdx)).";";
            }
        }
        return $sql;
    }
}
