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

use ArkonExample\Shared\Infrastructure\Bootstrap\Install\Installer;
use Arkonsoft\PsModule\Core\Tab\TabManager;
use Arkonsoft\PsModule\Core\Tab\TabManagerInterface;
use Arkonsoft\PsModule\DI\AutowiringContainer;

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

class ArkonExample extends Module
{
    public AutowiringContainer $moduleContainer;

    public function __construct()
    {
        $this->name = 'arkonexample';
        
        /**
         * @see Full list of available tabs: https://devdocs.prestashop-project.org/8/modules/concepts/module-class/#tab
         */
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Arkonsoft';
        $this->author_uri = 'https://arkonsoft.pl/';
        $this->need_instance = true;
        $this->bootstrap = true;
        $this->ps_versions_compliancy = ['min' => '8.0.0', 'max' => _PS_VERSION_];
        $this->dependencies = [];

        parent::__construct();

        $this->displayName = 'ArkonExample';
        $this->description = 'ArkonExample';
        $this->confirmUninstall = 'Jesteś pewien, że chcesz usunąć wszystkie dane?';

        $this->prepareContainer();
    }

    private function prepareContainer()
    {
        $this->moduleContainer = new AutowiringContainer();

        /* Parameters */
        $this->moduleContainer->setParameter('module_name', $this->name);
        $this->moduleContainer->setParameter('sql_dir', $this->getLocalPath() . '/sql');
        $this->moduleContainer->setParameter('settings_controller_class_name', basename(str_replace('Controller', '', AdminArkonExampleSettingsController::class)));

        /* Services */
        $this->moduleContainer->set(self::class, function () {
            return $this;
        });

        $this->moduleContainer->set(\ArkonExample::class, function () {
            return $this;
        });

        $this->moduleContainer->set(Db::class, function () {
            return Db::getInstance();
        });

        $this->moduleContainer->set(Context::class, function () {
            return $this->context;
        });

        $this->moduleContainer->set(TabManagerInterface::class, function () {
            return new TabManager($this);
        });
    }

    public function isUsingNewTranslationSystem(): bool
    {
        return true;
    }

    public function install()
    {
        if (!Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        if (!parent::install()) {
            return false;
        }

        return $this->moduleContainer->get(Installer::class)->install();
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }

        return $this->moduleContainer->get(Installer::class)->uninstall();
    }

    public function getContent()
    {
        Tools::redirectAdmin($this->context->link->getAdminLink($this->moduleContainer->get('%settings_controller_class_name%')));
    }
}
