<?php

namespace Tourze\WorkermanDoctrineBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\WorkermanDoctrineBundle\WorkermanDoctrineBundle;

class WorkermanDoctrineBundleTest extends TestCase
{
    public function testBundle_canBeInstantiated(): void
    {
        $bundle = new WorkermanDoctrineBundle();
        $this->assertInstanceOf(Bundle::class, $bundle);
        $this->assertInstanceOf(WorkermanDoctrineBundle::class, $bundle);
    }
} 