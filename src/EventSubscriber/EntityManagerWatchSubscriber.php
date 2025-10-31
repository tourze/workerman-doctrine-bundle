<?php

namespace Tourze\WorkermanDoctrineBundle\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\KernelEvents;
use Workerman\Worker;

/**
 * 因为 entityManager 会存在“发生异常就马上close这种问题”，为了简化我们的操作，我们在这里主动退出进程，减少问题被蔓延的可能
 */
readonly class EntityManagerWatchSubscriber
{
    public function __construct(private Registry $registry)
    {
    }

    #[AsEventListener(event: KernelEvents::TERMINATE, priority: -99999)]
    #[AsEventListener(event: ConsoleEvents::TERMINATE, priority: -99999)]
    public function checkEntityManager(): void
    {
        foreach ($this->registry->getManagers() as $manager) {
            // 确保我们处理的是 EntityManagerInterface，它有 isOpen() 方法
            if (!$manager instanceof EntityManagerInterface) {
                continue;
            }

            if ($manager->isOpen()) {
                continue;
            }

            if (Worker::isRunning()) {
                Worker::stopAll();
            }
        }
    }
}
