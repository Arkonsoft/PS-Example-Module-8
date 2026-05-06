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

namespace ArkonExample\Shared\Infrastructure\Bootstrap\Install;

use Arkonsoft\PsModule\Core\Tab\TabConfiguration;
use Arkonsoft\PsModule\Core\Tab\TabDictionary;
use Arkonsoft\PsModule\Core\Tab\TabManagerInterface;

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
        private readonly TabManagerInterface $tabManager,
    ) {
    }

    /**
     * @return TabConfiguration[]
     */
    private function getTabs(): array {
        return [
            
            /* Main module tab */
            new TabConfiguration(
                controllerClassName: (string) $this->module->name,
                tabName: (string) $this->module->displayName,
                tabParent: TabDictionary::PARENT_THEMES,
                shouldBeVisibleInMenu: true
            ),
            /* Settings tab */
            new TabConfiguration(
                controllerClassName: (string) $this->settingsControllerClassName,
                tabName: (string) $this->module->displayName,
                tabParent: (string) $this->module->name,
                shouldBeVisibleInMenu: true
            )
        ];
    }

    public function install(): bool
    {
        try {
            foreach ($this->getTabs() as $tab) {
                $this->tabManager->installTab(
                    controllerClassName: $tab->getControllerClassName(),
                    tabName: $tab->getTabName(),
                    tabParent: $tab->getTabParent(),
                    shouldBeVisibleInMenu: $tab->getShouldBeVisibleInMenu()
                );
            }   
        } catch (\Exception $e) {
            // @phpstan-ignore-next-line
            if (_PS_MODE_DEV_) {
                throw $e;
            }
            // @phpstan-ignore-next-line
            return false;
        }

        return true;
    }

    public function uninstall(): bool
    {
        try {
            foreach ($this->getTabs() as $tab) {
                $this->tabManager->uninstallTab(
                    controllerClassName: $tab->getControllerClassName()
                );
            }
        } catch (\Exception $e) {
            // @phpstan-ignore-next-line
            if (_PS_MODE_DEV_) {
                throw $e;
            }
            // @phpstan-ignore-next-line
            return false;
        }

        return true;
    }
}
