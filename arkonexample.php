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
 * @copyright 2024 Arkonsoft
 */

declare(strict_types=1);

use Arkonsoft\PsModule\Core\Module\AbstractModule;

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

class ArkonExample extends AbstractModule
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
        $this->description = $this->l('Example module for example purpose');
        $this->confirmUninstall = $this->l('Are you sure? All data will be lost!');
        $this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];

        $this->settingsAdminController = str_replace('Controller', '', AdminArkonExampleSettingsController::class);
    }

    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        return (parent::install()
            && $this->installTab()
            && $this->registerHook('moduleRoutes')
            && $this->registerHook('actionFrontControllerSetMedia')
        );
    }

    public function uninstall()
    {
        return (parent::uninstall()
            && $this->uninstallTab()
        );
    }

    public function hookModuleRoutes()
    {
        require_once $this->getLocalPath() . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
    }

    public function hookActionFrontControllerSetMedia()
    {
        $this->context->controller->registerStylesheet(
            $this->name . '-front',
            "modules/{$this->name}/views/css/front.css",
            [
                'media' => 'all',
                'priority' => 1000
            ]
        );

        $this->context->controller->registerJavascript(
            $this->name . '-front',
            "modules/{$this->name}/views/js/front.js",
            [
                'position' => 'bottom',
                'priority' => 1000
            ]
        );
    }

    public function installTab(): bool
    {
        if (Tab::getIdFromClassName($this->settingsAdminController)) {
            return true;
        }

        $tab = new Tab();

        $parentTabClassName = 'AdminCatalog';

        $tab->id_parent = (int)Tab::getIdFromClassName($parentTabClassName);
        $tab->name = [];

        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $this->displayName;
        }
        $tab->class_name = $this->settingsAdminController;
        $tab->module = $this->name;
        $tab->active = 1;

        return (bool) $tab->add();
    }

    public function uninstallTab(): bool
    {
        $id_tab = (int)Tab::getIdFromClassName($this->settingsAdminController);
        $tab = new Tab((int)$id_tab);

        return (bool) $tab->delete();
    }

    public function getContent()
    {
        Tools::redirectAdmin($this->context->link->getAdminLink($this->settingsAdminController));
    }
}
