<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licensed under the Software License Agreement.
 *
 * With the purchase or the installation of the software in your application
 * you accept the license agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 * @author Arkonsoft
 * @copyright 2025 Arkonsoft
 * @license Commercial - The terms of the license are subject to a proprietary agreement between the author (Arkonsoft) and the licensee
 */

declare(strict_types=1);

namespace ArkonExample\Application\Controller\Admin;

use ArkonExample\Infrastructure\Form\Settings\SettingsFormDictionary;
use ArkonExample\Infrastructure\Service\Settings;
use Arkonsoft\PsModule\Core\Controller\AbstractAdminSettingsController;

if (!defined('_PS_VERSION_')) {
    exit;
}

class SettingsAdminController extends AbstractAdminSettingsController
{
    /**
     * @var \ArkonExample
     */
    public $module;

    public function __construct()
    {
        parent::__construct();

        $this->meta_title = $this->module->displayName;
        $this->page_header_toolbar_title = $this->meta_title;
    }

    /**
     * @return void
     */
    public function prepareOptions()
    {
        $settings = $this->module->container->get(Settings::class);

        $form = [
            'form' => [
                'tabs' => [
                    'global' => 'Globalne',
                    // 'other1' => 'Inne 1',
                    // 'other2' => 'Inne 2',
                ],
                'legend' => [
                    'title' => 'Ustawienia',
                    'icon' => 'icon-cogs',
                ],
                'input' => [
                    [
                        'label' => 'Przykładowe pole tekstowe',
                        'type' => 'text',
                        'name' => $settings->getFieldFullName(SettingsFormDictionary::EXAMPLE_TEXT_FIELD->value),
                        'desc' => 'To jest przykładowy opis pola',
                        'lang' => true,
                        'tab' => 'global',
                    ],
                    [
                        'label' => 'Przykładowy przełącznik',
                        'type' => 'switch',
                        'name' => $settings->getFieldFullName(SettingsFormDictionary::EXAMPLE_SWITCHER->value),
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => $settings->getFieldFullName(SettingsFormDictionary::EXAMPLE_SWITCHER->value) . '_on',
                                'value' => 1,
                                'label' => 'Włączony',
                            ],
                            [
                                'id' => $settings->getFieldFullName(SettingsFormDictionary::EXAMPLE_SWITCHER->value) . '_off',
                                'value' => 0,
                                'label' => 'Wyłączony',
                            ],
                        ],
                        'tab' => 'global',
                    ],
                    // [
                    //     'label' => 'Przykładowe pole tekstowe 1',
                    //     'type' => 'text',
                    //     'name' => $settings->getFieldFullName(SettingsFormDictionary::EXAMPLE_TEXT_FIELD_1),
                    //     'desc' => 'To jest przykładowy opis pola',
                    //     'lang' => true,
                    //     'tab' => 'other1',
                    // ],
                    // [
                    //     'label' => 'Przykładowe pole tekstowe 2',
                    //     'type' => 'text',
                    //     'name' => $settings->getFieldFullName(SettingsFormDictionary::EXAMPLE_TEXT_FIELD_2)
                    //     'desc' => 'To jest przykładowy opis pola',
                    //     'lang' => true,
                    //     'tab' => 'other2',
                    // ],
                ],
                'submit' => [
                    'title' => 'Zapisz',
                    'class' => 'btn btn-default pull-right',
                ],
            ],
        ];

        $this->forms[] = $form;
    }
}
