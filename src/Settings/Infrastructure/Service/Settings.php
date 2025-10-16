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

namespace ArkonExample\Settings\Infrastructure\Service;

use ArkonExample\Settings\Domain\ValueObject\SettingsFormDictionary;

if (!defined('_PS_VERSION_')) {
    exit;
}

class Settings
{
    /**
     * @param string $moduleName %module_name%
     */
    public function __construct(private readonly string $moduleName)
    {
    }

    /**
     * @param string $field
     *
     * @return string Returns name of the field with module name prefix
     */
    public function getFieldFullName(string $field): string
    {
        if (empty($field)) {
            // @phpstan-ignore-next-line
            if (_PS_MODE_DEV_) {
                throw new \InvalidArgumentException('Field name cannot be empty');
            }

            // @phpstan-ignore-next-line
            return '';
        }

        return $this->moduleName . '_' . $field;
    }

    /**
     * ================= EXAMPLE =================
     * TODO: remove this example
     * ============================================
     *
     * @param int $langId
     *
     * @return string
     */
    public function getExampleTextFieldName(int $langId): string
    {
        $fieldName = $this->getFieldFullName(SettingsFormDictionary::EXAMPLE_TEXT_FIELD->value);
        $value = (string) \Configuration::get($fieldName, $langId);

        if (empty($value)) {
            return '';
        }

        return $value;
    }
}
