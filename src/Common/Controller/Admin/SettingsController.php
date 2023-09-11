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
 * @copyright 2023 Arkonsoft
 */

declare(strict_types=1);

namespace ArkonExample\Common\Controller\Admin;

use Arkonsoft\PsModule\Core\Controller\AbstractAdminSettingsController;

if (!defined('_PS_VERSION_')) {
    exit;
}

class SettingsAdminController extends AbstractAdminSettingsController
{

    public function __construct()
    {
        parent::__construct();

        $this->meta_title = $this->module->l('Example title');
        $this->page_header_toolbar_title = $this->meta_title;
    }

    public function prepareOptions()
    {
        $form = [
            'form' => [
                'tabs' => [
                    'global' => $this->module->l('Global', pathinfo(__FILE__, PATHINFO_FILENAME)),
                    'other1' => $this->module->l('Other 1', pathinfo(__FILE__, PATHINFO_FILENAME)),
                    'other2' => $this->module->l('Other 2', pathinfo(__FILE__, PATHINFO_FILENAME)),
                ],
                'legend' => [
                    'title' => $this->module->l('Settings', pathinfo(__FILE__, PATHINFO_FILENAME)),
                    'icon' => 'icon-cogs'
                ],
                'input' => [
                    [
                        'label' => $this->module->l('Example text field', pathinfo(__FILE__, PATHINFO_FILENAME)),
                        'type' => 'text',
                        'name' => $this->module->name . 'example_text_field',
                        'desc' => $this->module->l('This is example description of field', pathinfo(__FILE__, PATHINFO_FILENAME)),
                        'lang' => true,
                        'tab' => 'global'
                    ],
                    [
                        'label' => $this->module->l('Example switcher', pathinfo(__FILE__, PATHINFO_FILENAME)),
                        'type' => 'switch',
                        'name' => $this->module->name . 'example_switcher',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => $this->module->name . 'example_switcher_on',
                                'value' => 1,
                                'label' => $this->module->l('Enabled', pathinfo(__FILE__, PATHINFO_FILENAME))
                            ],
                            [
                                'id' => $this->module->name . 'example_switcher_off',
                                'value' => 0,
                                'label' => $this->module->l('Disabled', pathinfo(__FILE__, PATHINFO_FILENAME))
                            ]
                        ],
                        'tab' => 'global'
                    ],
                    [
                        'label' => $this->module->l('Example text field 1', pathinfo(__FILE__, PATHINFO_FILENAME)),
                        'type' => 'text',
                        'name' => $this->module->name . 'example_text_field1',
                        'desc' => $this->module->l('This is example description of field', pathinfo(__FILE__, PATHINFO_FILENAME)),
                        'lang' => true,
                        'tab' => 'other1'
                    ],
                    [
                        'label' => $this->module->l('Example text field 2', pathinfo(__FILE__, PATHINFO_FILENAME)),
                        'type' => 'text',
                        'name' => $this->module->name . 'example_text_field2',
                        'desc' => $this->module->l('This is example description of field, pathinfo(__FILE__, PATHINFO_FILENAME)'),
                        'lang' => true,
                        'tab' => 'other2'
                    ],
                ],
                'submit' => [
                    'title' => $this->module->l('Save', pathinfo(__FILE__, PATHINFO_FILENAME)),
                    'class' => 'btn btn-default pull-right'
                ],
            ],
        ];

        $this->forms[] = $form;
    }
}
