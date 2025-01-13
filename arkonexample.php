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
 */

declare(strict_types=1);

use ArkonExample\Infrastructure\Bootstrap\Install\Installer;
use Arkonsoft\PsModule\DI\AutowiringContainer;

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

class ArkonExample extends Module
{
    public AutowiringContainer $container;

    public function __construct()
    {
        $this->name = 'arkonexample';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Arkonsoft';
        $this->author_uri = 'https://arkonsoft.pl/';
        $this->need_instance = true;
        $this->bootstrap = true;
        $this->ps_versions_compliancy = ['min' => '8.0.0', 'max' => _PS_VERSION_];
        $this->dependencies = [];

        parent::__construct();

        $this->displayName = 'Przykładowy moduł';
        $this->description = 'Przykładowy moduł do celów demonstracyjnych';
        $this->confirmUninstall = 'Jesteś pewien, że chcesz usunąć wszystkie dane?';

        $this->prepareContainer();
    }

    private function prepareContainer()
    {
        $this->container = new AutowiringContainer();

        /* Parameters */
        $this->container->setParameter('module_name', $this->name);
        $this->container->setParameter('settings_controller_class_name', str_replace('Controller', '', AdminArkonExampleSettingsController::class));

        /* Services */
        $this->container->set(Db::class, function () {
            return Db::getInstance();
        });

        $this->container->set(self::class, function () {
            return $this;
        });
    }

    public function install()
    {
        if (!Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        if (!parent::install()) {
            return false;
        }

        return $this->container->get(Installer::class)->install();
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }

        return $this->container->get(Installer::class)->uninstall();
    }

    public function getContent()
    {
        Tools::redirectAdmin($this->context->link->getAdminLink($this->container->get('%settings_controller_class_name%')));
    }
}
