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

namespace ArkonExample\Infrastructure\Bootstrap\Install;

use ArkonExample\Infrastructure\Bootstrap\Install\Command\DbInstallerCommandInterface;

if (!defined('_PS_VERSION_')) {
    exit;
}

class DbInstaller implements InstallerInterface
{
    public function __construct(
        private \Db $db,
    ) {
    }

    public function install(): bool
    {
        $installCommands = [
        ];

        foreach ($installCommands as $command) {
            $this->executeCommand($command);
        }

        return true;
    }

    public function uninstall(): bool
    {
        $uninstallCommands = [
        ];

        foreach ($uninstallCommands as $command) {
            $this->executeCommand($command);
        }

        return true;
    }

    private function executeCommand(DbInstallerCommandInterface $command): bool
    {
        $result = $this->db->execute($command->getSql());

        if (!$result) {
            throw new \Exception('Failed to execute command: ' . $command->getName());
        }

        return true;
    }
}
