<?php
use Narrowspark\CS\Config\Config;

$header = <<<'EOF'
This file is part of Narrowspark Framework.

(c) Daniel Bannert <d.bannert@anolilab.de>

This source file is subject to the MIT license that is bundled
with this source code in the file LICENSE.
EOF;

$config = new Config($header, [
    'native_function_invocation' => [
        'exclude' => [
            'getcwd',
            'extension_loaded',
        ],
    ],
    'comment_to_phpdoc' => false,
    'final_class' => false,
    'heredoc_indentation' => false,
    'PhpCsFixerCustomFixers/no_commented_out_code' => false,
]);

$config->getFinder()
    ->files()
    ->in(__DIR__)
    ->exclude('build')
    ->exclude('vendor')
    ->exclude('src/Prefetcher/Common')
    ->notPath('src/Prefetcher/alias.php')
    ->exclude('src/Security/Common')
    ->notPath('src/Security/alias.php')
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$cacheDir = getenv('TRAVIS') ? getenv('HOME') . '/.php-cs-fixer' : __DIR__;

$config->setCacheFile($cacheDir . '/.php_cs.cache');

return $config;
