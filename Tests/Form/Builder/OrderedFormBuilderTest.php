<?php

/*
 * This file is part of the Ivory Ordered Form bundle package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\OrderedFormBundle\Tests\Form\Builder;

use Ivory\OrderedFormBundle\Form\Builder\OrderedFormBuilder;

/**
 * Ordered form builder test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class OrderedFormBuilderTest extends AbstractOrderedBuilderTest
{
    /** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface */
    protected $eventDisptacher;

    /** @var \Symfony\Component\Form\FormFactoryInterface */
    protected $factory;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->eventDisptacher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->factory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');

        $this->builder = new OrderedFormBuilder('foo', null, $this->eventDisptacher, $this->factory);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        unset($this->eventDisptacher);
        unset($this->factory);
    }
}
