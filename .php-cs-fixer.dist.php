<?php

declare(strict_types=1);

$moduleRoot = __DIR__ . '/prestashop/modules/arkonexample';

require_once $moduleRoot . '/vendor/autoload.php';

$config = new class extends PrestaShop\CodingStandards\CsFixer\Config {
    public function getRules()
    {
        return array_merge(parent::getRules(), [
            'blank_line_after_opening_tag' => false,
            'trailing_comma_in_multiline' => [
                'elements' => ['arrays'],
            ],
        ]);
    }
};

/** @var \Symfony\Component\Finder\Finder $finder */
$finder = $config->setUsingCache(true)->getFinder();
$finder->in($moduleRoot)->exclude(['vendor', 'node_modules']);

return $config;
