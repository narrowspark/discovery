<?php

declare(strict_types=1);

/**
 * Copyright (c) 2018-2020 Daniel Bannert
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/narrowspark/automatic
 */

namespace Narrowspark\Automatic\Tests\Installer;

use Narrowspark\Automatic\Installer\ConfiguratorInstaller;

/**
 * @internal
 *
 * @covers \Narrowspark\Automatic\Installer\AbstractInstaller
 * @covers \Narrowspark\Automatic\Installer\ConfiguratorInstaller
 *
 * @medium
 */
final class ConfiguratorInstallerTest extends AbstractInstallerTest
{
    /**
     * {@inheritdoc}
     */
    protected $installerClass = ConfiguratorInstaller::class;
}
