<?php namespace Config;

class Schema
{
    // Nur relevante Teile zeigen – Beispiel für Menütabellen
    public static function wrContent(): array
    {
        return [
            'wr_menu_source' => [
                'columns' => [
                    'id'         => 'BIGINT UNSIGNED NOT NULL AUTO_INCREMENT',
                    'section'    => "VARCHAR(64) NOT NULL",
                    'locale'     => "VARCHAR(8) NOT NULL DEFAULT 'de'",
                    'source'     => "MEDIUMTEXT NULL",
                    'updated_at' => "DATETIME NULL",
                ],
                'primary' => ['id'],
                'unique'  => [],
                'keys'    => [
                    'idx_section_locale' => ['section','locale'],
                ],
                'engine'  => 'InnoDB',
                'charset' => 'utf8mb4',
                'collate' => 'utf8mb4_unicode_ci',
            ],
            'wr_menu_lexikon_index' => [
                'columns' => [
                    'id'        => 'BIGINT UNSIGNED NOT NULL AUTO_INCREMENT',
                    'identifer' => 'VARCHAR(191) NOT NULL',
                    'title'     => 'VARCHAR(255) NULL',
                    'letter'    => 'CHAR(1) NULL',
                    'is_active' => 'TINYINT NOT NULL DEFAULT 1',
                ],
                'primary' => ['id'],
                'unique'  => ['uq_identifer' => ['identifer']],
                'keys'    => ['idx_letter_active' => ['letter','is_active']],
                'engine'  => 'InnoDB', 'charset'=>'utf8mb4','collate'=>'utf8mb4_unicode_ci',
            ],
            'wr_menu_region_index' => [
                'columns' => [
                    'id'               => 'BIGINT UNSIGNED NOT NULL AUTO_INCREMENT',
                    'identifer'        => 'VARCHAR(191) NOT NULL',
                    'title'            => 'VARCHAR(255) NULL',
                    'region_identifer' => 'VARCHAR(191) NULL',
                    'is_active'        => 'TINYINT NOT NULL DEFAULT 1',
                ],
                'primary' => ['id'],
                'unique'  => ['uq_identifer' => ['identifer']],
                'keys'    => ['idx_region_active' => ['region_identifer','is_active']],
                'engine'  => 'InnoDB', 'charset'=>'utf8mb4','collate'=>'utf8mb4_unicode_ci',
            ],
            'wr_menu_winzer_index' => [
                'columns' => [
                    'id'                 => 'BIGINT UNSIGNED NOT NULL AUTO_INCREMENT',
                    'identifer'          => 'VARCHAR(191) NOT NULL',
                    'title'              => 'VARCHAR(255) NULL',
                    'producer_identifer' => 'VARCHAR(191) NULL',
                    'is_active'          => 'TINYINT NOT NULL DEFAULT 1',
                ],
                'primary' => ['id'],
                'unique'  => ['uq_identifer' => ['identifer']],
                'keys'    => ['idx_producer_active' => ['producer_identifer','is_active']],
                'engine'  => 'InnoDB', 'charset'=>'utf8mb4','collate'=>'utf8mb4_unicode_ci',
            ],
        ];
    }
}
