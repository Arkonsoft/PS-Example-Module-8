<?php

declare(strict_types=1);

$moduleRoot = __DIR__ . '/prestashop/modules/arkonexample';

require_once $moduleRoot . '/vendor/autoload.php';

$config = new PrestaShop\CodingStandards\CsFixer\Config();

$config->setRules(array_merge($config->getRules(), [
    'blank_line_after_opening_tag' => false,
]));

/** @var \Symfony\Component\Finder\Finder $finder */
$finder = $config->setUsingCache(true)->getFinder();
$finder->in($moduleRoot)->exclude(['vendor', 'node_modules']);

return $config;
