<?php declare(strict_types=1);

/**
 * @todo Add description
 *
 * File name: ConfigurationController.php
 * Created:   2026-10-09 11:01:09
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

use PrestaShop\PrestaShop\Core\Form\FormHandlerInterface;
use PrestaShopBundle\Controller\Admin\PrestaShopAdminController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ConfigurationController extends PrestaShopAdminController
{
    public function index(
        Request              $request,
        #[Autowire(service: 'thegedevaid.configuration.form_data_handler')]
        FormHandlerInterface $dataHandler,
    ): Response {
        $form = $dataHandler->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $errors = $dataHandler->save($form->getData());

            if (!\is_array($errors) || $errors === []) {
                $this->addFlash('success', $this->trans('Successful update.', [], 'Admin.Notifications.Success'));

                return $this->redirectToRoute('thegedevaid_configuration_form');
            }

            $this->addFlashErrors($errors);
        }

        return $this->render('@Modules/thegedevaid/views/templates/admin/configuration.html.twig', [
            'configurationForm' => $form->createView(),
        ]);
    }
}
