<?php

namespace Tourze\WorkermanDoctrineBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\WorkermanDoctrineBundle\DependencyInjection\WorkermanDoctrineExtension;

class WorkermanDoctrineExtensionTest extends TestCase
{
    public function testLoad_registersServices(): void
    {
        $containerBuilder = new ContainerBuilder();
        $extension = new WorkermanDoctrineExtension();
        
        $extension->load([], $containerBuilder);
        
        // 检查服务是否已注册
        $this->assertTrue(
            $containerBuilder->hasDefinition('tourze.workerman_doctrine.event_subscriber.entity_manager_watch_subscriber') 
            || $containerBuilder->hasDefinition('Tourze\WorkermanDoctrineBundle\EventSubscriber\EntityManagerWatchSubscriber')
        );
    }
    
    public function testLoad_withEmptyConfigs_doesNotThrowException(): void
    {
        $containerBuilder = new ContainerBuilder();
        $extension = new WorkermanDoctrineExtension();
        
        $this->expectNotToPerformAssertions();
        $extension->load([], $containerBuilder);
    }
} 