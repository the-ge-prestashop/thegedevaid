<?php declare(strict_types=1);

/**
 * Holds the install/uninstall lifecycle and the PrestaShop entry points.
 * Every non-trivial behaviour is delegated to a dedicated service
 * resolved from the Symfony container.
 *
 * File name: Core.php
 * Created:   2026-10-07 14:03:26
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

namespace TheGe\PrestaShopModule\DevAid;

use PrestaShop\PrestaShop\Adapter\Configuration;
use PrestaShopBundle\Service\Routing\Router;
use TheGe\PrestaShopModule\DevAid\Configuration\ConfigurationSpec;
use TheGe\PrestaShopModule\DevAid\Contract\ModuleCore;
use TheGe\PrestaShopModule\DevAid\Exception\InvalidHookException;
use TheGe\PrestaShopModule\DevAid\Hook\HookActionAdminControllerSetMedia;
use TheGe\PrestaShopModule\DevAid\Utils\CoreUtils;


class Core extends \Module implements ModuleCore
{
    use HookActionAdminControllerSetMedia;

    private ?CoreUtils $utils = null;

    public function isUsingNewTranslationSystem(): bool
    {
        return true;
    }

    // --- Install/Uninstall

    public function install(): bool
    {
        foreach (ConfigurationSpec::cases() as $spec) {
            $this->configuration()->set($spec->dbKey(), $spec->defaultDbValue());
        }

        $hooks = $this->hooksOrThrow();

        return parent::install()
            && $this->registerHook($hooks);
    }

    public function uninstall(): bool
    {
        foreach (ConfigurationSpec::cases() as $spec) {
            $this->configuration()->remove($spec->dbKey());
        }

        return parent::uninstall();
    }

    // --- Configuration form

    public function getContent(): void
    {
        $this->redirectTo('thegedevaid_configuration_form');
    }

    // --- Getters

    public function isActive(): bool
    {
        $spec = ConfigurationSpec::active;

        return $spec->typedValue($this->configuration()->get($spec->dbKey()));
    }

    // --- Helpers

    /**
     * Validates every hook trait and returns the hook names.
     *
     * @return list<string>
     *
     * @throws InvalidHookException when a hook trait is misplaced or lacks its method
     */
    private function hooksOrThrow(): array
    {
        $hooks     = [];
        $namespace = __NAMESPACE__.'\\Hook\\';

        foreach ($this->traits() as $fqn) {
            $class = $this->classFromFQN($fqn);

            if (!str_starts_with($class, 'Hook')) {
                continue;
            }

            if (!str_starts_with($fqn, $namespace)) {
                throw new InvalidHookException('Hook trait '.$fqn.' must be under '.$namespace);
            }

            if (!method_exists($fqn, lcfirst($class))) {
                throw new InvalidHookException(
                    'Hook trait '.$fqn.' does not define method '.lcfirst($class).'().'
                );
            }

            $hooks[] = lcfirst(substr($class, 4));
        }

        return $hooks;
    }

    public function redirectTo(string $routeId): void
    {
        /** @todo Find $router class */
        $router = $this->service('router');

        if (!$router instanceof Router) {
            throw new \LogicException('Router not available');
        }

        \Tools::redirectAdmin($router->generate($routeId));
    }

    public function configuration(): Configuration
    {
        /** @var Configuration */
        return $this->service('prestashop.adapter.legacy.configuration');
    }

    public function context(): \Context
    {
        $context = $this->context;

        if (!$context instanceof \Context) {
            throw new \LogicException('Context not available.');
        }

        return $context;
    }

    /**
     * @param string        $serviceId Service ID in the Symfony container
     * @param ?class-string $className Service class constraint
     */
    public function service(string $serviceId, ?string $className = null): object
    {
        $service = $this->get($serviceId);

        if ($service === null || ($className !== null && !($service instanceof $className))) {
            throw new \LogicException("The '{$serviceId}' service is not available.");
        }

        return $service;
    }

    /** @return string Must be compatible with ModuleCore::getPathUri() */
    public function getPathUri()
    {
        return parent::getPathUri();
    }

    protected function utils(): CoreUtils
    {
        return $this->utils ??= new CoreUtils($this);
    }

    /** @return list<string> */
    private function traits(): array
    {
        $traits = class_uses(self::class, false);

        return $traits === false ? [] : array_values($traits);
    }

    private function classFromFQN(string $fqn): string
    {
        $name = strrchr($fqn, '\\');

        return $name === false ? $fqn : substr($name, 1);
    }
}
