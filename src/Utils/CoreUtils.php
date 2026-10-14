<?php declare(strict_types=1);

/**
 * Module helpers
 *
 * File name: Core.php
 * Created:   2026-10-13 12:09:06
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

use PrestaShop\PrestaShop\Core\Context\LegacyControllerContext;
use TheGe\PrestaShopModule\DevAid\Contract\ModuleCore;


class CoreUtils
{
    public function __construct(private readonly ModuleCore $core) {}

    public function path(): string
    {
        return $this->core->getPathUri();
    }

    public function addAdminStylesheet(StylesheetArgsInAdmin $options): void
    {
        /** @var \AdminController $controller */
        $controller = $this->controller();

        if ($controller instanceof \FrontController) {
            throw new \TypeError('FrontController available, use the addFrontendStylesheet() method');
        }

        $controller->addCSS(...$options->toArray());
    }

    public function addFrontendStylesheet(StylesheetArgsInFrontend $options): void
    {
        /** @var \FrontController $controller */
        $controller = $this->controller();

        if (!$controller instanceof \FrontController) {
            throw new \TypeError('FrontController not available');
        }

        $controller->registerStylesheet(...$options->toArray());
    }

    public function controller(): \AdminController|\FrontController|LegacyControllerContext
    {
        $controller = $this->core->context()->controller;

        if (!($controller instanceof \AdminController)
            && !($controller instanceof \FrontController)
            && !($controller instanceof LegacyControllerContext)
        ) {
            throw new \LogicException('Controller not available: '.get_debug_type($controller));
        }

        return $controller;
    }
}
