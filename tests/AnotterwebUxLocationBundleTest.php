<?php

namespace Anotterweb\UxLocation\Tests;

use Anotterweb\UxLocation\AnotterwebUxLocationBundle;
use PHPUnit\Framework\TestCase;

class AnotterwebUxLocationBundleTest extends TestCase
{
    public function testBundleIsInstantiable(): void
    {
        $bundle = new AnotterwebUxLocationBundle();
        $this->assertInstanceOf(AnotterwebUxLocationBundle::class, $bundle);
        $this->assertDirectoryExists($bundle->getPath());
    }
}
