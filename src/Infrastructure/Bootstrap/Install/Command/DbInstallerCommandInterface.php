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

namespace ArkonExample\Infrastructure\Bootstrap\Install\Command;

if (!defined('_PS_VERSION_')) {
    exit;
}

interface DbInstallerCommandInterface
{
    public function getName(): string;

    public function getSql(): string;
}
