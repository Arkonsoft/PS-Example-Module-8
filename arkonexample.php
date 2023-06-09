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

if (!defined('_PS_VERSION_')) {
    exit;
}

class ArkonExample extends Module
{
    public function __construct()
    {
        $this->name = 'arkonexample';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Arkonsoft';
        $this->author_uri = 'https://arkonsoft.pl/';
        $this->need_instance = 1;
        $this->bootstrap = 1;
        $this->dependencies = [];

        parent::__construct();

        $this->displayName = $this->l('Example module');
        $this->description = $this->l('Example module for test purpose');
        $this->confirmUninstall = $this->l('Are you sure? All data will be lost!');
        $this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];
        $this->settingsController = 'AdminArkonExampleSettings';
    }

    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        return (parent::install()
            && $this->registerHook('moduleRoutes')
            && $this->registerHook('actionFrontControllerSetMedia')
            && $this->installSettingsTab()
        );
    }

    public function uninstall()
    {
        return (parent::uninstall()
            && $this->uninstallSettingsTab()
        );
    }

    public function installSettingsTab()
    {
        $tab = new Tab();
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminParentThemes');
        $tab->name = [];
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $this->displayName;
        }
        $tab->class_name = $this->settingsController;
        $tab->module = $this->name;
        $tab->active = 1;
        return $tab->add();
    }

    public function uninstallSettingsTab()
    {
        $id_tab = (int)Tab::getIdFromClassName($this->settingsController);
        $tab = new Tab((int)$id_tab);
        return $tab->delete();
    }

    public function hookModuleRoutes()
    {
        require_once 'vendor/autoload.php';
    }

    public function actionFrontControllerSetMedia()
    {
        $this->context->controller->registerStylesheet(
            "module-{$this->name}-front",
            "modules/{$this->name}/views/css/front.css",
            [
                'media' => 'all',
                'priority' => 1000
            ]
        );

        $this->context->controller->registerJavascript(
            "module-{$this->name}-front",
            "modules/{$this->name}/views/js/front.js",
            [
                'position' => 'bottom',
                'priority' => 1000
            ]
        );
    }

    public function getContent()
    {
        Tools::redirect($this->context->link->getAdminLink($this->settingsController));
    }
}
