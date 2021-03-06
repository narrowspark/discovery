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

use Composer\Composer;
use Composer\Config;
use Composer\Downloader\DownloadManager;
use Composer\IO\BufferIO;
use Composer\IO\IOInterface;
use Composer\Package\RootPackageInterface;
use Mockery;
use Narrowspark\Automatic\Automatic;
use Narrowspark\Automatic\Common\ClassFinder;
use Narrowspark\Automatic\Configurator;
use Narrowspark\Automatic\Container;
use Narrowspark\Automatic\Contract\Configurator as ConfiguratorContract;
use Narrowspark\Automatic\Contract\PackageConfigurator as PackageConfiguratorContract;
use Narrowspark\Automatic\Installer\ConfiguratorInstaller;
use Narrowspark\Automatic\Installer\SkeletonInstaller;
use Narrowspark\Automatic\Lock;
use Narrowspark\Automatic\Operation\Install;
use Narrowspark\Automatic\Operation\Uninstall;
use Narrowspark\Automatic\PackageConfigurator;
use Narrowspark\Automatic\ScriptExecutor;
use Narrowspark\TestingHelper\Phpunit\MockeryTestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @internal
 *
 * @covers \Narrowspark\Automatic\Container
 *
 * @medium
 */
final class ContainerTest extends MockeryTestCase
{
    /** @var \Narrowspark\Automatic\Container */
    private $container;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $composer = new Composer();

        /** @var \Composer\Config|\Mockery\MockInterface $configMock */
        $configMock = Mockery::mock(Config::class);
        $configMock->shouldReceive('get')
            ->with('vendor-dir')
            ->andReturn('/vendor');
        $configMock->shouldReceive('get')
            ->with('cache-files-dir')
            ->andReturn('');
        $configMock->shouldReceive('get')
            ->with('disable-tls')
            ->andReturn(true);
        $configMock->shouldReceive('get')
            ->with('bin-dir')
            ->andReturn(__DIR__);
        $configMock->shouldReceive('get')
            ->with('bin-compat')
            ->andReturn(__DIR__);

        $composer->setConfig($configMock);

        /** @var \Composer\Package\RootPackageInterface|\Mockery\MockInterface $package */
        $package = Mockery::mock(RootPackageInterface::class);
        $package->shouldReceive('getExtra')
            ->andReturn([]);

        $composer->setPackage($package);

        /** @var \Composer\Downloader\DownloadManager|\Mockery\MockInterface $downloadManager */
        $downloadManager = Mockery::mock(DownloadManager::class);
        $downloadManager->shouldReceive('getDownloader')
            ->with('file');

        $composer->setDownloadManager($downloadManager);

        $this->container = new Container($composer, new BufferIO());
    }

    /**
     * @dataProvider provideContainerInstancesCases
     */
    public function testContainerInstances(string $key, $expected): void
    {
        $value = $this->container->get($key);

        if (\is_string($value) || \is_array($value)) {
            self::assertSame($expected, $value);
        } else {
            self::assertInstanceOf($expected, $value);
        }
    }

    /**
     * @return array
     */
    public static function provideContainerInstancesCases(): iterable
    {
        return [
            [Composer::class, Composer::class],
            [Config::class, Config::class],
            [IOInterface::class, BufferIO::class],
            ['vendor-dir', '/vendor'],
            [
                'composer-extra',
                [
                    Automatic::COMPOSER_EXTRA_KEY => [
                        'allow-auto-install' => false,
                        'dont-discover' => [],
                    ],
                ],
            ],
            [InputInterface::class, InputInterface::class],
            [Lock::class, Lock::class],
            [ClassFinder::class, ClassFinder::class],
            [ConfiguratorInstaller::class, ConfiguratorInstaller::class],
            [SkeletonInstaller::class, SkeletonInstaller::class],
            [ConfiguratorContract::class, Configurator::class],
            [Install::class, Install::class],
            [Uninstall::class, Uninstall::class],
            [ScriptExecutor::class, ScriptExecutor::class],
            [PackageConfiguratorContract::class, PackageConfigurator::class],
            [Filesystem::class, Filesystem::class],
        ];
    }

    public function testGetAll(): void
    {
        self::assertCount(16, $this->container->getAll());
    }
}
