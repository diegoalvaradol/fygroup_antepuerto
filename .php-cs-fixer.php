<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude([
        'vendor',
        'node_modules',
        'storage',
        'bootstrap/cache',
    ])
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setUsingCache(true)
    ->setCacheFile(__DIR__ . '/.php-cs-fixer.cache')
    ->setRules([
        // Base
        '@PSR12' => true,

        // Modern PHP
        'array_syntax' => ['syntax' => 'short'],
        'declare_strict_types' => true, // ⚠️ deja false si tienes legacy
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'no_unused_imports' => true,
        'single_quote' => true,
        'trailing_comma_in_multiline' => true,

        // Espacios / legibilidad
        'binary_operator_spaces' => ['default' => 'single_space'],
        'concat_space' => ['spacing' => 'one'],
        'cast_spaces' => true,
        'trim_array_spaces' => true,

        // Limpieza
        'no_trailing_whitespace' => true,
        'no_whitespace_in_blank_line' => true,
        'no_extra_blank_lines' => [
            'tokens' => ['extra', 'throw', 'use'],
        ],
        'no_empty_statement' => true,
        'no_useless_return' => true,
        'no_superfluous_phpdoc_tags' => true,
        'no_closing_tag' => true,

        // Estructura
        'blank_line_before_statement' => [
            'statements' => ['return', 'throw', 'try'],
        ],

        // Seguridad (puedes activar después)
        'strict_comparison' => false,
        'strict_param' => false,

        // Evitar diffs feos
        'align_multiline_comment' => false,
    ])
    ->setFinder($finder);
