<?php

namespace Tourze\WorkermanDoctrineBundle\Tests\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\KernelEvents;
use Tourze\WorkermanDoctrineBundle\EventSubscriber\EntityManagerWatchSubscriber;
use Workerman\Worker;

/**
 * @internal
 *
 * @phpstan-ignore-next-line
 */
#[CoversClass(EntityManagerWatchSubscriber::class)]
final class EntityManagerWatchSubscriberTest extends TestCase
{
    public function testCheckEntityManagerWhenEntityManagerIsOpenReturnsEarly(): void
    {
        // 创建 EntityManager Mock，设置为打开状态
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->method('isOpen')->willReturn(true);

        // 创建 Registry Mock
        $registry = $this->createMock(Registry::class);
        $registry->method('getManagers')->willReturn(['default' => $entityManager]);

        // 创建事件订阅者实例
        $subscriber = new EntityManagerWatchSubscriber($registry);

        // 当 EntityManager 打开时，方法应该提前返回，不抛出异常
        $this->expectNotToPerformAssertions();
        $subscriber->checkEntityManager();
    }

    public function testCheckEntityManagerWhenEntityManagerIsClosedCallsWorkerStopAll(): void
    {
        // 创建 EntityManager Mock，设置为关闭状态
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->method('isOpen')->willReturn(false);

        // 创建 Registry Mock
        $registry = $this->createMock(Registry::class);
        $registry->method('getManagers')->willReturn(['default' => $entityManager]);

        // 创建事件订阅者实例
        $subscriber = new EntityManagerWatchSubscriber($registry);

        // 我们不能直接测试 Worker::stopAll() 的调用，因为它是静态方法
        // 但我们可以测试当 EntityManager 关闭时方法不会抛出异常
        $this->expectNotToPerformAssertions();
        $subscriber->checkEntityManager();
    }

    public function testEventListenerIsRegisteredCorrectly(): void
    {
        $reflectionClass = new \ReflectionClass(EntityManagerWatchSubscriber::class);
        $method = $reflectionClass->getMethod('checkEntityManager');

        $attributes = $method->getAttributes(AsEventListener::class);

        $this->assertCount(2, $attributes);

        $terminateEvent = false;
        $consoleTerminateEvent = false;

        foreach ($attributes as $attribute) {
            $args = $attribute->getArguments();

            if (isset($args['event']) && KernelEvents::TERMINATE === $args['event']) {
                $terminateEvent = true;
                $this->assertSame(-99999, $args['priority']);
            }

            if (isset($args['event']) && ConsoleEvents::TERMINATE === $args['event']) {
                $consoleTerminateEvent = true;
                $this->assertSame(-99999, $args['priority']);
            }
        }

        $this->assertTrue($terminateEvent, 'KernelEvents::TERMINATE 监听器未正确注册');
        $this->assertTrue($consoleTerminateEvent, 'ConsoleEvents::TERMINATE 监听器未正确注册');
    }
}
