<?php

/*
 * This file is part of the Ivory Ordered Form bundle package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\OrderedFormBundle\Tests;

use Ivory\OrderedFormBundle\IvoryOrderedFormBundle;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class IvoryOrderedFormBundleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IvoryOrderedFormBundle
     */
    protected $bundle;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->bundle = new IvoryOrderedFormBundle();
    }

    public function testInheritance()
    {
        $this->assertInstanceOf('Symfony\Component\HttpKernel\Bundle\Bundle', $this->bundle);
    }
}
