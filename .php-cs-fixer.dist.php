<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('tests/Support/_generated')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'global_namespace_import' => true,
    ])
    ->setFinder($finder)
;
