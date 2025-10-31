<?php

namespace Tourze\WorkermanDoctrineBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\PHPUnitSymfonyUnitTest\AbstractDependencyInjectionExtensionTestCase;
use Tourze\WorkermanDoctrineBundle\DependencyInjection\WorkermanDoctrineExtension;

/**
 * @internal
 */
#[CoversClass(WorkermanDoctrineExtension::class)]
final class WorkermanDoctrineExtensionTest extends AbstractDependencyInjectionExtensionTestCase
{
    public function testLoadWithEmptyConfigsDoesNotThrowException(): void
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->setParameter('kernel.environment', 'test');
        $extension = new WorkermanDoctrineExtension();

        $this->expectNotToPerformAssertions();
        $extension->load([], $containerBuilder);
    }
}
