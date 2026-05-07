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

if (!defined('_PS_VERSION_')) {
    exit;
}

class Installer implements InstallerInterface
{
    /** @var DbInstaller */
    private $dbInstaller;

    /** @var HookInstaller */
    private $hookInstaller;

    /** @var TabInstaller */
    private $tabInstaller;

    /** @var DirectoryInstaller */
    private $directoryInstaller;

    public function __construct(
        DbInstaller $dbInstaller,
        HookInstaller $hookInstaller,
        TabInstaller $tabInstaller,
        DirectoryInstaller $directoryInstaller
    ) {
        $this->dbInstaller = $dbInstaller;
        $this->hookInstaller = $hookInstaller;
        $this->tabInstaller = $tabInstaller;
        $this->directoryInstaller = $directoryInstaller;
    }

    public function install(): bool
    {
        return
            $this->dbInstaller->install()
            && $this->hookInstaller->install()
            && $this->tabInstaller->install()
            && $this->directoryInstaller->install()
        ;
    }

    public function uninstall(): bool
    {
        return
            $this->dbInstaller->uninstall()
            && $this->hookInstaller->uninstall()
            && $this->tabInstaller->uninstall()
            && $this->directoryInstaller->uninstall()
        ;
    }
}
