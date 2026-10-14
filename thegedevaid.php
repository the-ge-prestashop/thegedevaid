<?php declare(strict_types=1);

/**
 * The main module class is kept in the global namespace, per PrestaShop's own module loading convention.
 * Everything else lives under the module namespace, autoloaded via composer.json/PSR-4.
 * Holds the module metadata, apprently PrestaShop cannot read it from anywhere else.
 *
 * File name: thegedevaid.php
 * Created:   2026-10-07 13:52:39
 * @author    Gabriel Tenita <g1704578400@tenita.eu>
 * @see       https://github.com/the-ge-prestashop/thegedevaid
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

defined('_PS_VERSION_') || exit;

require_once __DIR__.'/vendor/autoload.php';

class Thegedevaid extends TheGe\PrestaShopModule\DevAid\Core
{
    // PrestaShop needs the constructor to read the module version.
    public function __construct()
    {
        $this->name = 'thegedevaid';
        $this->tab = 'administration';
        $this->version = '0.0.1';
        $this->author = 'Gabriel Tenita';
        $this->ps_versions_compliancy = ['min' => '9.0.0', 'max' => '9.99.99'];
        $this->need_instance = 1;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->trans('* Development Aids', [], 'Modules.Thegedevaid.Admin');
        $this->description = $this->trans(
            'Adds development helpers to PrestaShop.',
            [],
            'Modules.Thegedevaid.Admin'
        );
    }
}
