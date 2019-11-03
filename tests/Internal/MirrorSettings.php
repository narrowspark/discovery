<?php

declare(strict_types=1);

/**
 * This file is part of Narrowspark Framework.
 *
 * (c) Daniel Bannert <d.bannert@anolilab.de>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Narrowspark\Automatic\Test\Internal;

final class MirrorSettings
{
    public const COMMON_OPTIONS = [
        'path' => 'Common',
        'namespace' => 'Automatic\\Common',
    ];

    public const MIRROR_LIST = [
        [
            'output' => [
                'path' => 'Prefetcher' . \DIRECTORY_SEPARATOR,
                'namespace' => 'Automatic\\Prefetcher\\Common',
            ],
            'mirror_list' => [
                'src/Common/Contract/Exception/Exception.php' => self::COMMON_OPTIONS,
                'src/Common/Contract/Exception/InvalidArgumentException.php' => self::COMMON_OPTIONS,
                'src/Common/Contract/Container.php' => self::COMMON_OPTIONS,
                'src/Common/AbstractContainer.php' => self::COMMON_OPTIONS,
                'src/Common/Contract/Resettable.php' => self::COMMON_OPTIONS,
                'src/Common/Traits/GetGenericPropertyReaderTrait.php' => self::COMMON_OPTIONS,
                'src/Common/Contract/Exception/RuntimeException.php' => self::COMMON_OPTIONS,
                'src/Common/Util.php' => self::COMMON_OPTIONS,
            ],
        ],
        [
            'output' => [
                'path' => 'Security' . \DIRECTORY_SEPARATOR,
                'namespace' => 'Automatic\\Security\\Common',
            ],
            'mirror_list' => [
                'src/Common/Contract/Exception/Exception.php' => self::COMMON_OPTIONS,
                'src/Common/Contract/Exception/InvalidArgumentException.php' => self::COMMON_OPTIONS,
                'src/Common/Contract/Container.php' => self::COMMON_OPTIONS,
                'src/Common/AbstractContainer.php' => self::COMMON_OPTIONS,
                'src/Common/Contract/Resettable.php' => self::COMMON_OPTIONS,
                'src/Common/Traits/GetGenericPropertyReaderTrait.php' => self::COMMON_OPTIONS,
                'src/Common/Contract/Exception/RuntimeException.php' => self::COMMON_OPTIONS,
                'src/Common/Util.php' => self::COMMON_OPTIONS,
            ],
        ],
    ];

    public const COMMENT_STRING = <<<'STRING'
/**
 * This file is automatically generated, dont change this file, otherwise the changes are lost after the next mirror update.
 *
 * @codeCoverageIgnore
 *
 * @internal
 */
STRING;
}
