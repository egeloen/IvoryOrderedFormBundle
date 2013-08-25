<?php

/*
 * This file is part of the Ivory Ordered Form bundle package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\OrderedFormBundle\Tests\Form\Orderer;

use Ivory\OrderedFormBundle\Form\Orderer\FormOrdererFactory;

/**
 * Form orderer factory test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class FormOrdererFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Ivory\OrderedFormBundle\Form\Orderer\FormOrdererFactory */
    protected $factory;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->factory = new FormOrdererFactory();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->factory);
    }

    public function testCreate()
    {
        $this->assertInstanceOf('Ivory\OrderedFormBundle\Form\Orderer\FormOrderer', $this->factory->create());
    }
}
