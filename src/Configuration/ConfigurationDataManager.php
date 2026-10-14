<?php declare(strict_types=1);

/**
 * Configuration is used to save data to configuration table and retrieve from it
 *
 * File name: ConfigurationDataManager.php
 * Created:   2026-10-09 11:16:18
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

namespace TheGe\PrestaShopModule\DevAid\Configuration;

use PrestaShop\PrestaShop\Core\Configuration\DataConfigurationInterface;
use PrestaShop\PrestaShop\Core\ConfigurationInterface;


final class ConfigurationDataManager implements DataConfigurationInterface
{
    public function __construct(
        private ConfigurationInterface $configuration,
    ) {}

    /** @return array{}|array{'active': string} */
    public function getConfiguration(): array
    {
        $return = [];

        if ($active = $this->configuration->get(ConfigurationSpec::active->dbKey())) {
            $return['active'] = $active;
        }

        return $return;
    }

    /**
     * @param array{'active': string} $configuration
     *
     * @return array<int, string>
     */
    public function updateConfiguration(array $configuration): array // @phpstan-ignore method.childParameterType
    {
        $this->configuration->set(ConfigurationSpec::active->dbKey(), $configuration['active']);

        return []; // errors
    }

    /**
     * Ensure the parameters passed are valid.
     *
     * @param array{'active': string} $configuration
     *
     * @return bool Returns true if no exception are thrown
     */
    public function validateConfiguration(array $configuration): bool // @phpstan-ignore method.childParameterType
    {
        return \array_key_exists('active', $configuration);
    }
}
