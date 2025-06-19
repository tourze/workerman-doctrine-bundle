<?php

namespace Tourze\WorkermanDoctrineBundle\Tests\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\KernelEvents;
use Tourze\WorkermanDoctrineBundle\EventSubscriber\EntityManagerWatchSubscriber;

class EntityManagerWatchSubscriberTest extends TestCase
{
    private EntityManagerInterface&MockObject $entityManager;
    private EntityManagerWatchSubscriber $subscriber;
    
    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->subscriber = new EntityManagerWatchSubscriber($this->entityManager);
    }
    
    public function testCheckEntityManager_whenEntityManagerIsOpen_returnsEarly(): void
    {
        $this->entityManager->expects($this->once())
            ->method('isOpen')
            ->willReturn(true);
            
        $this->subscriber->checkEntityManager();
        // 由于方法在 EntityManager 打开时提前返回，此处不需要额外断言
    }
    
    public function testCheckEntityManager_whenEntityManagerIsClosed_checksWorkermanStatus(): void
    {
        $this->entityManager->expects($this->once())
            ->method('isOpen')
            ->willReturn(false);
            
        // 对于 Worker 静态方法调用，我们无法在不修改代码的情况下进行测试
        // 这里只测试到 isOpen() 返回 false 的情况
        $this->subscriber->checkEntityManager();
    }
    
    public function testEventListener_isRegisteredCorrectly(): void
    {
        $reflectionClass = new \ReflectionClass(EntityManagerWatchSubscriber::class);
        $method = $reflectionClass->getMethod('checkEntityManager');
        
        $attributes = $method->getAttributes(AsEventListener::class);
        
        $this->assertCount(2, $attributes);
        
        $terminateEvent = false;
        $consoleTerminateEvent = false;
        
        foreach ($attributes as $attribute) {
            $instance = $attribute->newInstance();
            $args = $attribute->getArguments();
            
            if (isset($args['event']) && $args['event'] === KernelEvents::TERMINATE) {
                $terminateEvent = true;
                $this->assertSame(-99999, $args['priority']);
            }
            
            if (isset($args['event']) && $args['event'] === ConsoleEvents::TERMINATE) {
                $consoleTerminateEvent = true;
                $this->assertSame(-99999, $args['priority']);
            }
        }
        
        $this->assertTrue($terminateEvent, 'KernelEvents::TERMINATE 监听器未正确注册');
        $this->assertTrue($consoleTerminateEvent, 'ConsoleEvents::TERMINATE 监听器未正确注册');
    }
} 