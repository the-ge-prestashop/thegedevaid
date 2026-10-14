<?php declare(strict_types=1);

/**
 * Form data provider, in this case it's as simple as using configuration to do that
 *
 * File name: ConfigurationDataProvider
 * Created:   2026-10-09 11:14:04
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
use PrestaShop\PrestaShop\Core\Form\FormDataProviderInterface;


class ConfigurationDataProvider implements FormDataProviderInterface
{
    public function __construct(
        private DataConfigurationInterface $dataConfiguration,
    ) {}

    /** @return array<string, mixed> */
    public function getData(): array
    {
        return $this->dataConfiguration->getConfiguration();
    }

    /**
     * @param array{'active': string} $data
     *
     * @return array<int, string>
     */
    public function setData(array $data): array // @phpstan-ignore method.childParameterType
    {
        return $this->dataConfiguration->updateConfiguration($data);
    }
}
