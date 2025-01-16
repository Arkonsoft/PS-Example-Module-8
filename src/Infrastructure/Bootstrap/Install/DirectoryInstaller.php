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

if (!defined('_PS_VERSION_')) {
    exit;
}

class DirectoryInstaller implements InstallerInterface
{

    public function install(): bool
    {
        return true;
    }

    public function uninstall(): bool
    {
        return true;
    }
}
