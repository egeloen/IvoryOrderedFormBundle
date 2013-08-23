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

/**
 * Abstract ordered builder test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractOrderedBuilderTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Ivory\OrderedFormBundle\Form\Builder\OrderedFormConfigBuilderInterface */
    protected $builder;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->markTestSkipped('You must override the setUp method in order to instanciate your builder.');
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->builder);
    }

    public function testDefaultState()
    {
        $this->assertInstanceOf('\Ivory\OrderedFormBundle\Form\OrderedFormConfigInterface', $this->builder);
        $this->assertInstanceOf('\Ivory\OrderedFormBundle\Form\Builder\OrderedFormConfigBuilderInterface', $this->builder);

        $this->assertNull($this->builder->getPosition());
    }

    /**
     * @expectedException \Symfony\Component\Form\Exception\BadMethodCallException
     * @expectedExceptionMessage The config builder cannot be modified anymore.
     */
    public function testLockedPosition()
    {
        $config = $this->builder->getFormConfig();
        $config->setPosition('first');
    }

    public function testFirstPosition()
    {
        $this->builder->setPosition('first');

        $this->assertSame('first', $this->builder->getPosition());
    }

    public function testLastPosition()
    {
        $this->builder->setPosition('last');

        $this->assertSame('last', $this->builder->getPosition());
    }

    public function testBeforePosition()
    {
        $this->builder->setPosition(array('before' => 'foo'));

        $this->assertSame(array('before' => 'foo'), $this->builder->getPosition());
    }

    public function testAfterPosition()
    {
        $this->builder->setPosition(array('after' => 'foo'));

        $this->assertSame(array('after' => 'foo'), $this->builder->getPosition());
    }

    public function testFluentInterface()
    {
        $this->assertSame($this->builder, $this->builder->setPosition('first'));
    }

    /**
     * @expectedException \Ivory\OrderedFormBundle\Form\Exception\OrderedConfigurationException
     * @expectedExceptionMessage The "foo" form uses position as string which can only be "first" or "last" (current: "foo").
     */
    public function testInvalidStringPosition()
    {
        $this->builder->setPosition('foo');
    }

    /**
     * @expectedException \Ivory\OrderedFormBundle\Form\Exception\OrderedConfigurationException
     * @expectedExceptionMessage The "foo" form uses position as array or you must define the "before" or "after" option (current: "bar").
     */
    public function testInvalidArrayPosition()
    {
        $this->builder->setPosition(array('bar' => 'baz'));
    }
}
