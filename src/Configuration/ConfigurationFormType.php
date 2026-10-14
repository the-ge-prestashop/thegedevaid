<?php declare(strict_types=1);

/**
 * Symfony form describing the module configuration page.
 *
 * File name: ConfigurationFormType.php
 * Created:   2026-10-07 14:24:47
 * @author    Gabriel Tenita <g1704578400@tenita.eu>
 * @see       https://github.com/the-ge/
 * @copyright Copyright (c) 2026-present Gabriel Tenita
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License version 2.0
 */

namespace TheGe\PrestaShopModule\DevAid\Configuration;

use PrestaShopBundle\Form\Admin\Type\SwitchType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


final class ConfigurationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('active', SwitchType::class, [
            'label'    => 'Active',
            'help'     => 'Enable or disable Dev Aid.',
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'Modules.Thegedevaid.Admin',
        ]);
    }
}
