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

use Doctrine\DBAL\Connection;
use PrestaShop\PrestaShop\Adapter\Configuration;
use PrestaShopBundle\Service\Routing\Router;
use TheGe\PrestaShopModule\DevAid\Configuration\ConfigurationSpec;
use TheGe\PrestaShopModule\DevAid\Contract\DataManager;
use TheGe\PrestaShopModule\DevAid\Contract\PrestaShopModule;
use TheGe\PrestaShopModule\DevAid\Exception\ContainerUnavailableException;
use TheGe\PrestaShopModule\DevAid\Exception\InvalidHookException;
use TheGe\PrestaShopModule\DevAid\Hook\HookActionAdminControllerSetMedia;
use TheGe\PrestaShopModule\DevAid\Utils\CoreUtils;
use TheGe\PrestaShopModule\DevAid\Utils\SQLDataManager;


class Core extends \Module implements PrestaShopModule
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
        if (!parent::install()) {
            return false;
        }

        foreach (ConfigurationSpec::cases() as $spec) {
            $this->configuration()->set($spec->dbKey(), $spec->defaultDbValue());
        }

        $hooks = $this->hooksOrThrow();

        $errors = $this->getDataManager()->initialize();
        if ($errors !== []) {
            $this->_errors = array_merge($this->_errors, $errors, $this->getDataManager()->clear());

            return false;
        }

        return !(!$this->registerHook($hooks))
        ;
    }

    public function uninstall(): bool
    {
        if (!parent::uninstall()) {
            return false;
        }

        foreach (ConfigurationSpec::cases() as $spec) {
            $this->configuration()->remove($spec->dbKey());
        }

        $errors = $this->getDataManager()->clear();
        if ($errors !== []) {
            $this->_errors = array_merge($this->_errors, $errors);

            return false;
        }

        return true;
    }

    private function getDataManager(): DataManager
    {
        try {
            $manager = $this->get(SQLDataManager::class);
        } catch (\Throwable) {
            // Catch exception in case container is not available, or service is not available
            $manager = null;
        }

        // During install process the modules's service is not available yet, so we build it manually
        if (!$manager instanceof SQLDataManager) {
            /** @var string */
            $dbPrefix = $this->getContainer()->getParameter('database_prefix');
            $manager  = new SQLDataManager($this->db(), $dbPrefix);
        }

        return $manager;
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
        $router = $this->service('router', Router::class);

        if (!$router instanceof Router) {
            throw new \LogicException('Router not available');
        }

        \Tools::redirectAdmin($router->generate($routeId));
    }

    public function configuration(): Configuration
    {
        return $this->service('prestashop.adapter.legacy.configuration', Configuration::class);
    }

    public function context(): \Context
    {
        $context = $this->context;

        if (!$context instanceof \Context) {
            throw new \LogicException('Context not available.');
        }

        return $context;
    }

    public function db(): Connection
    {
        $db = $this->service('doctrine.dbal.default_connection', Connection::class);

        return $db;
    }

    public function service(string $serviceId, ?string $className = null): object
    {
        $service = $this->get($serviceId);

        if ($service === null) {
            throw new ContainerUnavailableException("Container not available. Attempted to load '{$serviceId}'.");
        }

        $realClass = $service::class;

        if ($className === null) {
            if (!str_contains($serviceId, '\\')) {
                \PrestaShopLogger::addLog(
                    "Missing class name for this service. ID: {$serviceId}, real class: {$realClass}.",
                    1,
                );
            }
        } elseif (!($service instanceof $className)) {
            throw new \LogicException(
                "Incorrect service class. ID: {$serviceId}, correct class: {$className}, real class: {$realClass}."
            );
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
