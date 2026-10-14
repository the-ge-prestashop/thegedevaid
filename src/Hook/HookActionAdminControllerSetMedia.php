<?php declare(strict_types=1);

/**
 * @todo add hook description
 * Trait for easy adding/removing a hook to a module, by adding:
 *      1. the 'use' import statement in the main module file,
 *      2. the trait 'use' statement in the module class,
 * Usage: backend.
 *
 * File name: HookActionAdminControllerSetMedia.php
 * Created:   2026-10-13 14:11:00
 * @author    Gabriel Tenita <the.ge.1447624801@tenita.eu>
 * @see      https://github.com/the-ge/
 * @copyright Copyright (c) 2024-present Gabriel Tenita
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License version 2.0
 */

namespace TheGe\PrestaShopModule\DevAid\Hook;

use TheGe\PrestaShopModule\DevAid\Utils\CoreUtils;
use TheGe\PrestaShopModule\DevAid\Utils\StylesheetArgsInAdmin;


trait HookActionAdminControllerSetMedia
{
    abstract protected function utils(): CoreUtils;

    /**
     * @param array{
     *  '_ps_version' ?: string,
     *  'request'     ?: \Symfony\Component\HttpFoundation\Request,
     *  'route'       ?: string,
     *  'cookie'       : \Cookie,
     *  'cart'         : null,
     *  'altern'       : int,
     * }  $params
     */
    public function hookActionAdminControllerSetMedia(array $params): void
    {
        $this->utils()->addAdminStylesheet(new StylesheetArgsInAdmin("{$this->utils()->path()}views/css/admin.css"));
    }
}
