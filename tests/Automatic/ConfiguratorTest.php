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

namespace Narrowspark\Automatic\Tests;

use Composer\IO\IOInterface;
use Narrowspark\Automatic\Common\Contract\Configurator as ConfiguratorContract;
use Narrowspark\Automatic\Common\Package;
use Narrowspark\Automatic\Configurator;

/**
 * @internal
 *
 * @covers \Narrowspark\Automatic\Configurator
 *
 * @medium
 */
final class ConfiguratorTest extends AbstractConfiguratorTest
{
    /** @var string */
    private $copyFileName;

    /** @var string */
    private $copyPath;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->copyFileName = 'copy_of_copy.txt';
        $this->copyPath = \sys_get_temp_dir() . '/' . $this->copyFileName;
    }

    public function testConfigureWithCopy(): void
    {
        $this->arrangeVendorDir();

        $package = $this->arrangeCopyPackage();

        $this->ioMock->shouldReceive('writeError')
            ->once()
            ->with(['    - Copying files'], true, IOInterface::VERBOSE);

        $this->ioMock->shouldReceive('writeError')
            ->once()
            ->with(['    - Created <fg=green>"' . $this->copyFileName . '"</>'], true, IOInterface::VERBOSE);

        $this->configurator->configure($package);

        self::assertFileExists($this->copyPath);

        \unlink($this->copyPath);
    }

    public function testGetConfigurators(): void
    {
        self::assertCount(5, $this->configurator->getConfigurators());
    }

    public function testUnconfigureWithCopy(): void
    {
        $this->arrangeVendorDir();

        $package = $this->arrangeCopyPackage();

        $this->ioMock->shouldReceive('writeError')
            ->once()
            ->with(['    - Copying files'], true, IOInterface::VERBOSE);

        $this->ioMock->shouldReceive('writeError')
            ->once()
            ->with(['    - Created <fg=green>"' . $this->copyFileName . '"</>'], true, IOInterface::VERBOSE);

        $this->configurator->configure($package);

        self::assertFileExists($this->copyPath);

        $this->ioMock->shouldReceive('writeError')
            ->once()
            ->with(['    - Removing files'], true, IOInterface::VERBOSE);

        $this->ioMock->shouldReceive('writeError')
            ->once()
            ->with(['    - Removed <fg=green>"' . $this->copyFileName . '"</>'], true, IOInterface::VERBOSE);

        $this->configurator->unconfigure($package);

        self::assertFileNotExists($this->copyPath);
    }

    /**
     * {@inheritdoc}
     */
    protected function allowMockingNonExistentMethods($allow = false): void
    {
        parent::allowMockingNonExistentMethods(true);
    }

    protected function arrangeCopyPackage(): Package
    {
        $package = new Package('Fixture/copy', '1.0');
        $package->setConfig([
            ConfiguratorContract::TYPE => [
                'copy' => [
                    'copy.txt' => $this->copyFileName,
                ],
            ],
        ]);

        return $package;
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfiguratorClass(): string
    {
        return Configurator::class;
    }

    private function arrangeVendorDir(): void
    {
        $this->composerMock->shouldReceive('getConfig->get')
            ->with('vendor-dir')
            ->andReturn(__DIR__);
    }
}
