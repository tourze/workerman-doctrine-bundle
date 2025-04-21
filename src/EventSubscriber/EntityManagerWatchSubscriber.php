<?php

namespace Tourze\WorkermanDoctrineBundle\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\KernelEvents;
use Workerman\Worker;

/**
 * 因为 entityManager 会存在“发生异常就马上close这种问题”，为了简化我们的操作，我们在这里主动退出进程，减少问题被蔓延的可能
 */
class EntityManagerWatchSubscriber
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[AsEventListener(event: KernelEvents::TERMINATE, priority: -99999)]
    #[AsEventListener(event: ConsoleEvents::TERMINATE, priority: -99999)]
    public function checkEntityManager(): void
    {
        if ($this->entityManager->isOpen()) {
            return;
        }

        if (Worker::isRunning()) {
            Worker::stopAll();
        }
    }
}
