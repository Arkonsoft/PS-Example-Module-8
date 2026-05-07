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
 * @copyright 2026 Arkonsoft
 * @license Commercial - The terms of the license are subject to a proprietary agreement between the author (Arkonsoft) and the licensee
 */

declare(strict_types=1);

namespace ArkonExample\Shared\Infrastructure\Bootstrap\Install;

use Arkonsoft\PsModule\Core\Tab\TabConfiguration;
use Arkonsoft\PsModule\Core\Tab\TabDictionary;

if (!defined('_PS_VERSION_')) {
    exit;
}

class TabInstaller implements InstallerInterface
{
    /** @var \ArkonExample */
    private $module;

    /** @var string */
    private $settingsControllerClassName;

    /**
     * @param string $settingsControllerClassName %settings_controller_class_name%
     */
    public function __construct(
        \ArkonExample $module,
        string $settingsControllerClassName
    ) {
        $this->module = $module;
        $this->settingsControllerClassName = $settingsControllerClassName;
    }

    /**
     * @return TabConfiguration[]
     */
    private function getTabs(): array
    {
        return [
            /* Main module tab */
            new TabConfiguration(
                (string) $this->module->name,
                (string) $this->module->displayName,
                TabDictionary::PARENT_THEMES,
                true
            ),
            /* Settings tab */
            new TabConfiguration(
                (string) $this->settingsControllerClassName,
                (string) $this->module->displayName,
                (string) $this->module->name,
                true
            ),
        ];
    }

    public function install(): bool
    {
        try {
            foreach ($this->getTabs() as $tab) {
                $this->installTab(
                    $tab->getControllerClassName(),
                    $tab->getTabName(),
                    $tab->getTabParent(),
                    $tab->getShouldBeVisibleInMenu()
                );
            }
        } catch (\Exception $e) {
            if (_PS_MODE_DEV_) {
                throw $e;
            }

            return false;
        }

        return true;
    }

    public function uninstall(): bool
    {
        try {
            foreach ($this->getTabs() as $tab) {
                $this->uninstallTab($tab->getControllerClassName());
            }
        } catch (\Exception $e) {
            if (_PS_MODE_DEV_) {
                throw $e;
            }

            return false;
        }

        return true;
    }

    public function installTab(
        string $controllerClassName,
        string $tabName,
        string $tabParent,
        bool $shouldBeVisibleInMenu
    ): bool {
        if ($this->getIdByControllerClassName($controllerClassName)) {
            return true;
        }

        $tab = new \Tab();
        $tab->id_parent = (int) $this->getIdByControllerClassName($tabParent);
        $tab->name = [];

        if (is_array($tabName)) {
            $tab->name = $tabName;
        } else {
            foreach (\Language::getLanguages(true, false, true) as $langId) {
                $tab->name[(int) $langId] = $tabName;
            }
        }

        $tab->class_name = $controllerClassName;
        $tab->module = $this->module->name;
        $tab->active = $shouldBeVisibleInMenu;

        return (bool) $tab->add();
    }

    /**
     * @param string $controllerClassName
     *
     * @return bool
     */
    public function uninstallTab($controllerClassName): bool
    {
        $tabId = (int) $this->getIdByControllerClassName($controllerClassName);

        $tab = new \Tab((int) $tabId);

        return (bool) $tab->delete();
    }

    public function getIdByControllerClassName($controllerClassName): int
    {
        return (int) \Tab::getIdFromClassName($controllerClassName);
    }
}
