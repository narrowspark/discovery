<?php
declare(strict_types=1);
namespace Narrowspark\Discovery\Test\Configurator;

use Composer\Composer;
use Composer\IO\NullIO;
use Narrowspark\Discovery\Configurator\GitIgnoreConfigurator;
use Narrowspark\Discovery\Package;
use PHPUnit\Framework\TestCase;

class GitignoreConfiguratorTest extends TestCase
{
    /**
     * @var \Composer\Composer
     */
    private $composer;

    /**
     * @var \Composer\IO\NullIo
     */
    private $nullIo;

    /**
     * @var \Narrowspark\Discovery\Configurator\GitignoreConfigurator
     */
    private $configurator;

    /**
     * @var string
     */
    private $gitignorePath;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->composer = new Composer();
        $this->nullIo   = new NullIO();

        $this->configurator = new GitIgnoreConfigurator($this->composer, $this->nullIo, ['public-dir' => 'public']);

        $this->gitignorePath = sys_get_temp_dir() . '/.gitignore';

        \touch($this->gitignorePath);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        \unlink($this->gitignorePath);
    }

    public function testConfigureAndUnconfigure(): void
    {
        $package = new Package(
            'FooBundle',
            __DIR__,
            [
                'package_version'  => '1',
                Package::CONFIGURE => [
                    'git_ignore' => [
                        '.env',
                        '/%PUBLIC_DIR%/css/',
                    ],
                ],
            ]
        );

        $gitignoreContents1 = <<<'EOF'
###> FooBundle ###
.env
/public/css/
###< FooBundle ###
EOF;

        $package2 = new Package(
            'BarBundle',
            __DIR__,
            [
                'package_version'  => '1',
                Package::CONFIGURE => [
                    'git_ignore' => [
                        '/var/',
                        '/vendor/',
                    ],
                ],
            ]
        );

        $gitignoreContents2 = <<<'EOF'
###> BarBundle ###
/var/
/vendor/
###< BarBundle ###
EOF;

        $this->configurator->configure($package);

        self::assertStringEqualsFile($this->gitignorePath, "\n" . $gitignoreContents1 . "\n");

        $this->configurator->configure($package2);

        self::assertStringEqualsFile($this->gitignorePath, "\n" . $gitignoreContents1 . "\n\n" . $gitignoreContents2 . "\n");

        $this->configurator->configure($package);
        $this->configurator->configure($package2);

        self::assertStringEqualsFile($this->gitignorePath, "\n" . $gitignoreContents1 . "\n\n" . $gitignoreContents2 . "\n");

        $this->configurator->unconfigure($package);

        self::assertStringEqualsFile($this->gitignorePath, $gitignoreContents2 . "\n");

        $this->configurator->unconfigure($package2);

        self::assertStringEqualsFile($this->gitignorePath, '');
    }
}
