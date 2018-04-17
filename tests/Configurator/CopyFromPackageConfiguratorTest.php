<?php
declare(strict_types=1);
namespace Narrowspark\Discovery\Test\Configurator;

use Composer\Composer;
use Composer\IO\IOInterface;
use Narrowspark\Discovery\Configurator\CopyFromPackageConfigurator;
use Narrowspark\Discovery\Package;
use Narrowspark\TestingHelper\Phpunit\MockeryTestCase;

class CopyFromPackageConfiguratorTest extends MockeryTestCase
{
    /**
     * @var \Composer\Composer
     */
    private $composer;

    /**
     * @var \Composer\IO\NullIo
     */
    private $ioMock;

    /**
     * @var \Narrowspark\Discovery\Configurator\CopyFromPackageConfigurator
     */
    private $configurator;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->composer = new Composer();
        $this->ioMock   = $this->mock(IOInterface::class);

        $this->configurator = new CopyFromPackageConfigurator($this->composer, $this->ioMock, ['self-dir' => 'test']);
    }

    public function testCopyFileFromPackage(): void
    {
        $toFileName = 'copy_of_copy.txt';

        $package = new Package(
            'Fixtures',
            __DIR__,
            [
                'version'          => '1',
                'url'              => 'example.local',
                'type'             => 'library',
                'operation'        => 'i',
                'copy'             => [
                    'copy.txt' => $toFileName,
                ],
            ]
        );

        $this->ioMock->shouldReceive('writeError')
            ->once()
            ->with(['    Copying files'], true, IOInterface::VERBOSE);
        $this->ioMock->shouldReceive('writeError')
            ->once()
            ->with(['    Created <fg=green>"copy_of_copy.txt"</>'], true, IOInterface::VERBOSE);

        $this->configurator->configure($package);

        $filePath = \sys_get_temp_dir() . '/' . $toFileName;

        self::assertFileExists($filePath);

        \unlink($filePath);
    }

    public function testCopyADirWithFileFromPackage(): void
    {
        $toAndFromFileName = '/css/style.css';

        $package = new Package(
            'Fixtures',
            __DIR__,
            [
                'version'          => '1',
                'url'              => 'example.local',
                'type'             => 'library',
                'operation'        => 'i',
                'copy'             => [
                    $toAndFromFileName => $toAndFromFileName,
                ],
            ]
        );

        $this->ioMock->shouldReceive('writeError')
            ->once()
            ->with(['    Copying files'], true, IOInterface::VERBOSE);
        $this->ioMock->shouldReceive('writeError')
            ->once()
            ->with(['    Created <fg=green>"/css/style.css"</>'], true, IOInterface::VERBOSE);

        $this->configurator->configure($package);

        $dirPath  = \sys_get_temp_dir() . '/css';
        $filePath = $dirPath . '/style.css';

        self::assertDirectoryExists($dirPath);
        self::assertFileExists($filePath);

        \unlink($filePath);
        \rmdir($dirPath);
    }

    public function testTryCopyAFileThatIsNotFoundFromPackage(): void
    {
        $toFileName = 'notfound.txt';

        $package = new Package(
            'Fixtures',
            __DIR__,
            [
                'version'   => '1',
                'url'       => 'example.local',
                'type'      => 'library',
                'operation' => 'i',
                'copy'      => [
                    $toFileName => $toFileName,
                ],
            ]
        );

        $this->ioMock->shouldReceive('writeError')
            ->once()
            ->with(['    Copying files'], true, IOInterface::VERBOSE);
        $this->ioMock->shouldReceive('writeError')
            ->once()
            ->with(['    <fg=red>Failed to create "notfound.txt"</>; Error message: Failed to copy "' . __DIR__ . '/Fixtures/notfound.txt" because file does not exist.'], true, IOInterface::VERBOSE);

        $this->configurator->configure($package);

        $filePath = \sys_get_temp_dir() . '/' . $toFileName;

        self::assertFileNotExists($filePath);
    }

    public function testUnconfigureAFileFromPackage(): void
    {
        $toFileName = 'copy_of_copy.txt';

        $package = new Package(
            'Fixtures',
            __DIR__,
            [
                'version'   => '1',
                'url'       => 'example.local',
                'type'      => 'library',
                'operation' => 'i',
                'copy'      => [
                    'copy.txt' => $toFileName,
                ],
            ]
        );

        $this->ioMock->shouldReceive('writeError')
            ->once()
            ->with(['    Copying files'], true, IOInterface::VERBOSE);
        $this->ioMock->shouldReceive('writeError')
            ->once()
            ->with(['    Created <fg=green>"copy_of_copy.txt"</>'], true, IOInterface::VERBOSE);

        $this->configurator->configure($package);

        $this->ioMock->shouldReceive('writeError')
            ->once()
            ->with(['    Removing files'], true, IOInterface::VERBOSE);
        $this->ioMock->shouldReceive('writeError')
            ->once()
            ->with(['    Removed <fg=green>"copy_of_copy.txt"</>'], true, IOInterface::VERBOSE);

        $this->configurator->unconfigure($package);
    }

    public function testUnconfigureADirWithFileFromPackage(): void
    {
        $toAndFromFileName = '/css/style.css';

        $package = new Package(
            'Fixtures',
            __DIR__,
            [
                'version'   => '1',
                'url'       => 'example.local',
                'type'      => 'library',
                'operation' => 'i',
                'copy'      => [
                    $toAndFromFileName => $toAndFromFileName,
                ],
            ]
        );

        $this->ioMock->shouldReceive('writeError')
            ->once()
            ->with(['    Copying files'], true, IOInterface::VERBOSE);
        $this->ioMock->shouldReceive('writeError')
            ->once()
            ->with(['    Created <fg=green>"/css/style.css"</>'], true, IOInterface::VERBOSE);

        $this->configurator->configure($package);

        $this->ioMock->shouldReceive('writeError')
            ->once()
            ->with(['    Removing files'], true, IOInterface::VERBOSE);
        $this->ioMock->shouldReceive('writeError')
            ->once()
            ->with(['    Removed <fg=green>"/css/style.css"</>'], true, IOInterface::VERBOSE);

        $this->configurator->unconfigure($package);

        $dirPath = \sys_get_temp_dir() . '/css';

        self::assertDirectoryExists($dirPath);

        \rmdir($dirPath);
    }

    public function testCopyFileFromPackageWithConfig(): void
    {
        $toFileName = 'copy_of_copy.txt';

        $package = new Package(
            'Fixtures',
            __DIR__,
            [
                'version'   => '1',
                'url'       => 'example.local',
                'type'      => 'library',
                'operation' => 'i',
                'copy'      => [
                    'copy.txt' => '%SELF_DIR%/' . $toFileName,
                ],
            ]
        );

        $this->ioMock->shouldReceive('writeError')
            ->once()
            ->with(['    Copying files'], true, IOInterface::VERBOSE);
        $this->ioMock->shouldReceive('writeError')
            ->once()
            ->with(['    Created <fg=green>"test/copy_of_copy.txt"</>'], true, IOInterface::VERBOSE);

        $this->configurator->configure($package);

        $filePath = \sys_get_temp_dir() . '/test/' . $toFileName;

        self::assertFileExists($filePath);

        \unlink($filePath);
        \rmdir(\sys_get_temp_dir() . '/test/');
    }

    /**
     * {@inheritdoc}
     */
    protected function assertPreConditions(): void
    {
        parent::assertPreConditions();

        $this->allowMockingNonExistentMethods(true);
    }
}