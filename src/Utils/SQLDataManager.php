<?php declare(strict_types=1);

/**
 * We cannot use Doctrine entities on install because the mapping is not available yet
 * but we can still use Doctrine connection to perform DQL or SQL queries.
 *
 * File name: SQLDataManager.php
 * Created:   2026-10-14 16:14:37
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

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DBALException;
use TheGe\PrestaShopModule\DevAid\Contract\DataManager;


class SQLDataManager implements DataManager
{
    private string $tableName = 'thegedevaid';

    private SQLTableSet $tableset;

    public function __construct(
        private readonly Connection $connection,
        private readonly string     $dbPrefix,
    ) {
        $this->tableset = new SQLTableSet(
            $this->tableName,
            'id_patch',
            [],
            ['description' => 'text DEFAULT NULL'],
            ['active' => 'tinyint(1) unsigned NOT NULL DEFAULT 0'],
            [
                '(1)',
                "(1,1,'Adaugă numele directorului fiecărui modul lângă numele său afișat în lista din secțiunea Îmbunătățește > Module > Administrare module.'), (1,2, 'Add each module directory name next to its displayed name in the Improve > Modules > Manage list.')",
                '(1,1,1)',
            ]
        );
    }

    /** @return string[] */
    public function initialize(): array
    {
        $errors = $this->clear();

        $errors = $this->tryQuery($this->tableset->main->getCreateQuery($this->dbPrefix), $errors);
        $errors = $this->tryQuery($this->tableset->lang->getCreateQuery($this->dbPrefix), $errors);
        $errors = $this->tryQuery($this->tableset->shop->getCreateQuery($this->dbPrefix), $errors);

        $errors = $this->tryQuery($this->tableset->main->getInitQuery($this->dbPrefix), $errors);
        $errors = $this->tryQuery($this->tableset->lang->getInitQuery($this->dbPrefix), $errors);
        $errors = $this->tryQuery($this->tableset->shop->getInitQuery($this->dbPrefix), $errors);

        return $errors;
    }

    /** @return string[] */
    public function clear(): array
    {
        $errors = [];
        $errors = $this->tryQuery($this->tableset->main->getDeleteQuery($this->dbPrefix), $errors);
        $errors = $this->tryQuery($this->tableset->lang->getDeleteQuery($this->dbPrefix), $errors);
        $errors = $this->tryQuery($this->tableset->shop->getDeleteQuery($this->dbPrefix), $errors);

        return $errors;
    }

    /**
     * @param  string[] $errors
     * @return string[]
     */
    private function tryQuery(string $query, array $errors): ?array
    {
        $error = null;

        try {
            $this->connection->executeQuery($query);
        } catch (DBALException $e) {
            $error = $e->getMessage();
        }

        if ($error !== null) {
            $errors[] = $error;
        }

        return $errors;
    }
}
