<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude([
        'vendor',
        'node_modules',
        'storage',
        'bootstrap/cache',
    ]);

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setUsingCache(true)
    ->setRules([

        // Base estándar
        '@PSR12' => true,

        // Código moderno y limpio
        'array_syntax' => ['syntax' => 'short'],
        'declare_strict_types' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'no_unused_imports' => true,
        'single_quote' => true,
        'trailing_comma_in_multiline' => true,

        // Espacios y formato
        'binary_operator_spaces' => ['default' => 'single_space'],
        'concat_space' => ['spacing' => 'one'],
        'cast_spaces' => true,
        'trim_array_spaces' => true,
        'no_trailing_whitespace' => true,
        'no_whitespace_in_blank_line' => true,

        // Estructura
        'blank_line_before_statement' => [
            'statements' => ['return', 'throw', 'try'],
        ],
        'no_extra_blank_lines' => [
            'tokens' => ['extra', 'throw', 'use'],
        ],

        // Limpieza
        'no_closing_tag' => true,
        'no_empty_statement' => true,
        'no_superfluous_phpdoc_tags' => true,
        'no_useless_return' => true,

        // Seguridad / buenas prácticas
        'strict_comparison' => true,
        'strict_param' => true,

        // Evita formateos feos
        'align_multiline_comment' => false,
    ])
    ->setFinder($finder);
