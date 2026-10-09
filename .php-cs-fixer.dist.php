<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in([__DIR__ . '/app', __DIR__ . '/framework', __DIR__ . '/configs', __DIR__ . '/tests'])
    ->append([__DIR__ . '/public/index.php']);

return new Config()
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        '@PSR12:risky' => true,
        'modifier_keywords' => ['elements' => ['const', 'method']],
        'declare_strict_types' => true,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'no_unused_imports' => true,
        'trailing_comma_in_multiline' => true,
        'single_quote' => true,
        'no_superfluous_phpdoc_tags' => true,
        'phpdoc_align' => false,
        'blank_line_after_opening_tag' => true,
        'concat_space' => ['spacing' => 'none'],
    ])
    ->setFinder($finder);
