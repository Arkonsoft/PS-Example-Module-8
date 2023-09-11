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

use ArkonExample\Common\Service\SettingsService;
use Arkonsoft\PsModule\Core\Module\AbstractModule;
use Arkonsoft\PsModule\DI\Container;
use Arkonsoft\PsModule\DI\ContainerInterface;

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

class ArkonExample extends AbstractModule
{
    /**
     * @var ContainerInterface
     */
    public $container = null;

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

        $this->container = new Container();
        $this->container->set('settings', SettingsService::class, [$this]);
    }

    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        return (parent::install()
            && $this->registerHook('moduleRoutes')
            && $this->container->get('settings')->install()
        );
    }

    public function uninstall()
    {
        return (parent::uninstall()
            && $this->container->get('settings')->uninstall()
        );
    }

    public function hookModuleRoutes()
    {
        require_once $this->getLocalPath() . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
    }
}
