<?php declare(strict_types=1);

/**
 * File name: SQLTableSet.php
 * Created:   2026-10-18 13:27:37
 * @author    Gabriel Tenita <g1704578400@tenita.eu>
 * @see       https://github.com/the-ge/
 * @copyright Copyright (c) 2026-present Gabriel Tenita
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0) that is bundled with this package in the
 * LICENSE.md file.  It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0. If you did not receive a copy of the license and are unable to obtain it
 * through the world-wide-web, please send an email to license@prestashop.com so we can send you a copy immediately.
 */

namespace TheGe\PrestaShopModule\DevAid\Utils;


final readonly class SQLTableSet
{
    public ?SQLTableDTO $main;

    public ?SQLTableDTO $lang;

    public ?SQLTableDTO $shop;

    /**
     * Data types are [field name => field data type] arrays. If a data
     * types array argument is empty, its associated SQLTableDTO is null.
     *
     * @param string                $name          main table name
     * @param string                $primaryIndex  primary index column name
     * @param array<string, string> $mainDataTypes main table data types array
     * @param array<string, string> $langDataTypes lang table data types array
     * @param array<string, string> $shopDataTypes shop table data types array
     * @param array{
     *      0: string,
     *      1: string,
     *      2: string
     * }                            $initValues    init values array (0: main, 1: lang, 2: shop)
     * @param string $options table options
     */
    public function __construct(
        public string $name,
        string        $primaryIndex,
        array         $mainDataTypes = [],
        array         $langDataTypes = [],
        array         $shopDataTypes = [],
        array         $initValues = [],
        string        $options = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4',
    ) {
        $indexDataType = 'int(10) unsigned NOT NULL';

        $this->main = new SQLTableDTO(
            $this->name,
            $options,
            array_merge([
                $primaryIndex => $indexDataType.' AUTO_INCREMENT',
            ], $mainDataTypes),
            [$primaryIndex],
            [],
            $initValues[0] ?? null,
        );

        $this->lang = $langDataTypes === [] ? null : new SQLTableDTO(
            $this->name.'_lang',
            $options,
            array_merge([
                $primaryIndex => $indexDataType,
                'id_lang'     => $indexDataType,
            ], $langDataTypes),
            [$primaryIndex, 'id_lang'],
            [$primaryIndex, 'id_lang'],
            $initValues[1] ?? null,
        );

        $this->shop = $shopDataTypes === [] ? null : new SQLTableDTO(
            $this->name.'_shop',
            $options,
            array_merge([
                $primaryIndex => $indexDataType,
                'id_shop'     => $indexDataType,
            ], $shopDataTypes),
            [$primaryIndex, 'id_shop'],
            [$primaryIndex, 'id_shop'],
            $initValues[2] ?? null,
        );
    }
}


final readonly class SQLTableDTO
{
    public function __construct(
        public string  $name,
        public string  $options,
        public array   $dataTypes,
        public array   $primary = [],
        public array   $indexes = [],
        public ?string $values = null,
    ) {}

    public function getCreateQuery(string $prefix): string
    {
        $indexesLine = $this->indexes === [] ? '' : ",\n  {$this->indexesToQuery()}";
        $primaryLine = $this->primary === [] ? '' : ",\n  {$this->primaryToQuery()}";

        return <<< SQL
            CREATE TABLE `{$prefix}{$this->name}` (
              {$this->fieldsToQuery()}{$indexesLine}{$primaryLine}
            ) {$this->options};
            SQL;
    }

    public function getDeleteQuery(string $prefix): string
    {
        return <<< SQL
            DROP TABLE IF EXISTS `{$prefix}{$this->name}`;
            SQL;
    }

    public function getInitQuery(string $prefix): string
    {
        if ($this->values === null) {
            return '';
        }

        return <<< SQL
            INSERT IGNORE INTO `{$prefix}{$this->name}`
              {$this->fieldsToQuery()},
              VALUES
              {$this->values};
            SQL;
    }

    private function fieldsToQuery(): string
    {
        return implode(', ', array_map(
            fn($k) => $k.' '.$this->dataTypes[$k],
            array_keys($this->dataTypes)
        ));
    }

    private function primaryToQuery(): string
    {
        return $this->primary === []
            ? ''
            : 'PRIMARY KEY ('.implode(', ', array_map(fn($v) => "`{$v}`", $this->primary)).')';
    }

    private function indexesToQuery(): string
    {
        return $this->indexes === []
            ? ''
            : implode(', ', array_map(fn($v) => "INDEX {$this->indexName($v)} (`{$v}`)", $this->indexes));
    }

    private function indexName(string $key): string
    {
        return strtoupper("IDX_{$this->name}{$key}");
    }
}
