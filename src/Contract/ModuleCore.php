<?php declare(strict_types=1);

/**
 * @todo Add description
 *
 * File name: ModuleCore.php
 * Created:   2026-10-13 12:33:30
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

namespace TheGe\PrestaShopModule\DevAid\Contract;

use PrestaShop\PrestaShop\Adapter\Configuration;


interface ModuleCore
{
    /** @return string Must be compatible with ModuleCore::getPathUri() */
    public function getPathUri();

    public function get(string $serviceId): ?object;

    public function configuration(): Configuration;

    public function context(): \Context;

    /**
     * @template T of object
     * @param string           $serviceId Service ID in the Symfony container (FQN or dotted string)
     * @param ?class-string<T> $className Optional service class constraint
     *
     * @return ($className is null ? object : T)
     *
     * @throws \TheGe\PrestaShopModule\DevAid\Exception\ContainerUnavailableException When no container is available
     * @throws \LogicException When the service is not an instance of $className
     */
    public function service(string $serviceId, ?string $className = null): object;
}
