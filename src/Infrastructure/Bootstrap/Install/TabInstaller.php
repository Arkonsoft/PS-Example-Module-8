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
        if (\Tab::getIdFromClassName($this->settingsControllerClassName)) {
            return true;
        }

        $tab = new \Tab();
        $tab->id_parent = (int) \Tab::getIdFromClassName(TabDictionary::PARENT_THEMES);
        $tab->name = [];

        foreach (\Language::getLanguages(true, false, true) as $langId) {
            $tab->name[(int) $langId] = $this->module->displayName;
        }

        $tab->class_name = $this->settingsControllerClassName;
        $tab->module = $this->module->name;
        $tab->active = true;

        return (bool) $tab->add();
    }

    public function uninstall(): bool
    {
        $id_tab = (int) \Tab::getIdFromClassName($this->settingsControllerClassName);

        $tab = new \Tab((int) $id_tab);

        return (bool) $tab->delete();
    }
}
