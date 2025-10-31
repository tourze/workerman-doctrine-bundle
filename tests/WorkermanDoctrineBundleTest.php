<?php

declare(strict_types=1);

namespace Tourze\WorkermanDoctrineBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\WorkermanDoctrineBundle\WorkermanDoctrineBundle;

/**
 * @internal
 *
 * @phpstan-ignore-next-line
 */
#[CoversClass(WorkermanDoctrineBundle::class)]
final class WorkermanDoctrineBundleTest extends TestCase
{
    public function testGetPathReturnsCorrectPath(): void
    {
        $bundle = new WorkermanDoctrineBundle();
        $path = $bundle->getPath();
        $this->assertStringContainsString('workerman-doctrine-bundle', $path);
        $this->assertDirectoryExists($path);
    }

    public function testBootDoesNotThrowException(): void
    {
        $bundle = new WorkermanDoctrineBundle();
        // 测试 boot 方法不会抛出异常
        $this->expectNotToPerformAssertions();
        $bundle->boot();
    }

    public function testShutdownDoesNotThrowException(): void
    {
        $bundle = new WorkermanDoctrineBundle();
        // 测试 shutdown 方法不会抛出异常
        $this->expectNotToPerformAssertions();
        $bundle->shutdown();
    }

    public function testBundleCanBeInstantiatedMultipleTimes(): void
    {
        $bundle1 = new WorkermanDoctrineBundle();
        $bundle2 = new WorkermanDoctrineBundle();

        $this->assertInstanceOf(WorkermanDoctrineBundle::class, $bundle1);
        $this->assertInstanceOf(WorkermanDoctrineBundle::class, $bundle2);
        $this->assertNotSame($bundle1, $bundle2);
    }
}
