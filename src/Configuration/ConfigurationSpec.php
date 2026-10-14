<?php declare(strict_types=1);

/**
 * PrestaShop database configuration keys and helpers for this module.
 *
 * File name: ConfigurationSpec.php
 * Created:   2026-10-07 14:07:03
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


enum ConfigurationSpec: string
{
    case active = 'THEGEDEVAID_ACTIVE'; // \Configuration value: bool

    /** NOTE: Modify return type to fit configuration option types */
    public function defaultValue(): false
    {
        return match ($this) {
            self::active => false,
        };
    }

    public function defaultDbValue(): string
    {
        return match ($this) {
            self::active => $this->dbValue($this->defaultValue()),
        };
    }

    // --- Value conversions

    /** NOTE: Modify $value type to fit configuration option types */
    public function dbValue(bool $value): string
    {
        return match ($this) {
            self::active => self::fromBool($value),
        };
    }

    /**
     * NOTE: Modify return type to fit configuration option types
     *
     * @param  string $value String configuration value as returned by \Configuration::get()
     * @return bool   Type coerced configuration value
     */
    public function typedValue(string $value): bool
    {
        return match ($this) {
            self::active => self::toBool($value),
        };
    }

    // --- Helpers

    public static function fromBool(bool $value): string
    {
        return $value ? '1' : '';
    }

    public static function toBool(string $value): bool
    {
        return $value === '1';
    }

    public function key(): string
    {
        return $this->name;
    }

    public function dbKey(): string
    {
        return $this->value;
    }
}
