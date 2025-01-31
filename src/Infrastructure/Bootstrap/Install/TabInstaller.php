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

namespace ArkonExample\Infrastructure\Bootstrap\Install;

use Arkonsoft\PsModule\Core\Tab\TabDictionary;

if (!defined('_PS_VERSION_')) {
    exit;
}

class TabInstaller implements InstallerInterface
{
    /**
     * @param string $settingsControllerClassName %settings_controller_class_name%
     */
    public function __construct(
        private readonly \ArkonExample $module,
        private readonly string $settingsControllerClassName,
    ) {
    }

    public function install(): bool
    {
        return (
            $this->installTab(
                $this->settingsControllerClassName,
                $this->module->displayName,
                TabDictionary::PARENT_THEMES,
                false
            )
        );
    }

    public function uninstall(): bool
    {
        return (
            $this->uninstallTab($this->settingsControllerClassName)
        );
    }

    private function installTab(string $controllerClassName, string|array $tabName, string $tabParent, bool $shouldBeVisibleInMenu): bool {
        if (\Tab::getIdFromClassName($controllerClassName)) {
            return true;
        }

        $tab = new \Tab();
        $tab->id_parent = (int) \Tab::getIdFromClassName($tabParent);
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

    private function uninstallTab(string $controllerClassName): bool {
        $tabId = (int) \Tab::getIdFromClassName($controllerClassName);

        $tab = new \Tab((int) $tabId);

        return (bool) $tab->delete();
    }
}
