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

use PrestaShopBundle\Install\SqlLoader;

if (!defined('_PS_VERSION_')) {
    exit;
}

class DbInstaller implements InstallerInterface
{
    /**
     * @param \Db $db
     * @param string $sqlDir %sql_dir%
     *
     * @return void
     */
    public function __construct(
        private \Db $db,
        private string $sqlDir,
    ) {
    }

    public function install(): bool
    {
        return $this->executeSqlFile($this->sqlDir . '/install.sql');
    }

    public function uninstall(): bool
    {
        return $this->executeSqlFile($this->sqlDir . '/uninstall.sql');
    }

    public function executeSqlFile(string $sqlFile): bool
    {
        if (!file_exists($sqlFile)) {
            throw new \Exception("SQL file $sqlFile not found");
        }

        $sql = $this->getSqlFromFile($sqlFile);

        if (empty($sql)) {
            throw new \Exception("File $sqlFile is empty");
        }

        // Use SqlLoader for multiple SQL statements
        $sqlLoader = new SqlLoader($this->db);
        $sqlLoader->setMetaData([
            '_DB_PREFIX_' => _DB_PREFIX_,
            '_MYSQL_ENGINE_' => _MYSQL_ENGINE_,
        ]);

        // Parse the SQL content directly
        $result = $sqlLoader->parse($sql, true);

        if (!$result) {
            $errors = $sqlLoader->getErrors();
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = 'Error: ' . $error['error'] . ' in query: ' . $error['query'];
            }
            throw new \Exception('SQL execution failed: ' . implode('; ', $errorMessages));
        }

        return true;
    }

    private function getSqlFromFile(string $file): string
    {
        $sql = file_get_contents($file);

        return (string) $sql;
    }
}
