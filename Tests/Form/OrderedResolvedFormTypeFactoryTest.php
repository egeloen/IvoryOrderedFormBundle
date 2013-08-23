<?php

/*
 * This file is part of the Ivory Ordered Form bundle package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\OrderedFormBundle\Tests\Form;

use Ivory\OrderedFormBundle\Form\OrderedResolvedFormTypeFactory;

/**
 * Ordered resolved form type factory test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class OrderedResolvedFormTypeFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Ivory\OrderedFormBundle\Form\OrderedResolvedFormTypeFactory */
    protected $resolvedFactory;

    /** @var \Ivory\OrderedFormBundle\Form\Orderer\FormOrdererFactoryInterface */
    protected $ordererFactory;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->ordererFactory = $this->getMock('Ivory\OrderedFormBundle\Form\Orderer\FormOrdererFactoryInterface');
        $this->resolvedFactory = new OrderedResolvedFormTypeFactory($this->ordererFactory);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->ordererFactory);
        unset($this->resolvedFactory);
    }

    public function testCreate()
    {
        $this->ordererFactory
            ->expects($this->once())
            ->method('create')
            ->will($this->returnValue($this->getMock('Ivory\OrderedFormBundle\Form\Orderer\FormOrdererInterface')));

        $this->assertInstanceOf(
            'Ivory\OrderedFormBundle\Form\OrderedResolvedFormType',
            $this->resolvedFactory->createResolvedType(
                $this->getMock('Symfony\Component\Form\FormTypeInterface'),
                array()
            )
        );
    }
}
