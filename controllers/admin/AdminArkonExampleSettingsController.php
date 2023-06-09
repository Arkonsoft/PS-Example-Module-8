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

if (!defined('_PS_VERSION_')) {
    exit;
}

class AdminArkonExampleSettingsController extends ArkonExample\Core\AbstractAdminSettingsController {

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
                    'global' => $this->module->l('Global'),
                    'other1' => $this->module->l('Other 1'),
                    'other2' => $this->module->l('Other 2'),
                ],
                'legend' => [       
                    'title' => $this->module->l('Settings'),  
                    'icon' => 'icon-cogs'   
                ],   
                'input' => [       
                    [       
                        'label' => $this->module->l('Example text field'),    
                        'type' => 'text',
                        'name' => $this->module->name . 'example_text_field',
                        'desc' => $this->module->l('This is example description of field'),
                        'lang' => true,
                        'tab' => 'global'
                    ],
                    [
                        'label' => $this->module->l('Example switcher'),
                        'type' => 'switch',
                        'name' => $this->module->name . 'example_switcher',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => $this->module->name . 'example_switcher_on',
                                'value' => 1,
                                'label' => $this->module->l('Enabled')
                            ],
                            [
                                'id' => $this->module->name . 'example_switcher_off',
                                'value' => 0,
                                'label' => $this->module->l('Disabled')
                            ]
                        ],
                        'tab' => 'global'
                    ],
                    [       
                        'label' => $this->module->l('Example text field 1'),    
                        'type' => 'text',
                        'name' => $this->module->name . 'example_text_field1',
                        'desc' => $this->module->l('This is example description of field'),
                        'lang' => true,
                        'tab' => 'other1'
                    ],
                    [       
                        'label' => $this->module->l('Example text field 2'),    
                        'type' => 'text',
                        'name' => $this->module->name . 'example_text_field2',
                        'desc' => $this->module->l('This is example description of field'),
                        'lang' => true,
                        'tab' => 'other2'
                    ],
                ],
                'submit' => [
                    'title' => $this->module->l('Save'),       
                    'class' => 'btn btn-default pull-right'   
                ],
            ],
        ];

        $this->forms[] = $form;
    }
}