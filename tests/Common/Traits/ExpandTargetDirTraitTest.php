<?php
declare(strict_types=1);
namespace Narrowspark\Discovery\Common\Test\Traits;

use Narrowspark\Discovery\Common\Traits\ExpandTargetDirTrait;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ExpandTargetDirTraitTest extends TestCase
{
    use ExpandTargetDirTrait;

    public function testItCanIdentifyVarsInTargetDir(): void
    {
        static::assertSame('bar', self::expandTargetDir(['foo' => 'bar/'], '%foo%'));
    }
}
