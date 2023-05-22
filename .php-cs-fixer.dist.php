<?php
declare(strict_types=1);

$rules = [
    '@PhpCsFixer' => true,
    '@PSR12' => true,
    'strict_param' => true,
    'array_syntax' => ['syntax' => 'short'],
    'declare_strict_types' => true,
];

$finder = PhpCsFixer\Finder::create()
    ->exclude(['tools', 'vendor'])
    ->notPath('*.cache')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true)
    ->name('*.php')
    ->in(__DIR__)
;

$config = new PhpCsFixer\Config();
return $config->setRules($rules)
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setUsingCache(true)
    ;