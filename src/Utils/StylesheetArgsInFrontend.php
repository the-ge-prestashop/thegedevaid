<?php declare(strict_types=1);

/**
 * @todo Add description
 *
 * File name: StylesheetArgsInFrontend.php
 * Created:   2026-10-13 14:55:21
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


final class StylesheetArgsInFrontend
{
    /**
     * @param string $relativePath Relative to directories used in FrontController constructor asset managers instantiation
     * @param array{
     *     'media'   ?: string,
     *     'priority'?: int,
     *     'inline'  ?: bool,
     *     'server'  ?: string,
     *     'needRtl' ?: bool,
     *     'version' ?: ?string,
     * } $params
     */
    public function __construct(
        public string $id,
        public string $relativePath,
        public array  $params = [],
    ) {}

    /** @return list{string, string, array{
     *     'media'   ?: string,
     *     'priority'?: int,
     *     'inline'  ?: bool,
     *     'server'  ?: string,
     *     'needRtl' ?: bool,
     *     'version' ?: ?string,
     * }}
     */
    public function toArray(): array
    {
        return [
            $this->id,
            $this->relativePath,
            $this->params,
        ];
    }
}
