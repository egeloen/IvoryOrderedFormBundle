<?php

/*
 * This file is part of the Ivory Ordered Form bundle package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\OrderedFormBundle\Tests\Form\Extension;

use Ivory\OrderedFormBundle\Form\Extension\OrderedButtonExtension;
use Ivory\OrderedFormBundle\Form\Extension\OrderedFormExtension;
use Ivory\OrderedFormBundle\Form\Builder\OrderedFormBuilder;
use Ivory\OrderedFormBundle\Form\OrderedResolvedFormTypeFactory;
use Ivory\OrderedFormBundle\Form\Orderer\FormOrdererFactory;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Ordered form extension test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class OrderedFormExtensionTest extends TypeTestCase
{
    /** @var \Symfony\Component\Form\DataMapperInterface */
    protected $dataMapper;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->factory = Forms::createFormFactoryBuilder()
            ->setResolvedTypeFactory(new OrderedResolvedFormTypeFactory(new FormOrdererFactory()))
            ->addTypeExtension(new OrderedFormExtension())
            ->addTypeExtension(new OrderedButtonExtension())
            ->getFormFactory();

        $this->dataMapper = $this->getMock('Symfony\Component\Form\DataMapperInterface');

        $this->builder = new OrderedFormBuilder(null, null, $this->dispatcher, $this->factory);
        $this->builder
            ->setCompound(true)
            ->setDataMapper($this->dataMapper);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        unset($this->dataMapper);
    }

    /**
     * Form types data provider.
     *
     * @return array The form types.
     */
    public function formTypeProvider()
    {
        return array(
            array('text'),
            array('button'),
        );
    }

    /**
     * @dataProvider formTypeProvider
     */
    public function testEmptyPosition($type)
    {
        $form = $this->builder->create('foo', $type)->getForm();

        $this->assertNull($form->getConfig()->getPosition());
    }

    /**
     * @dataProvider formTypeProvider
     */
    public function testStringPosition($type)
    {
        $form = $this->builder->create('foo', $type, array('position' => 'first'))->getForm();

        $this->assertSame('first', $form->getConfig()->getPosition());
    }

    /**
     * @dataProvider formTypeProvider
     */
    public function testArrayPosition($type)
    {
        $form = $this->builder->create('foo', $type, array('position' => array('before' => 'bar')))->getForm();

        $this->assertSame(array('before' => 'bar'), $form->getConfig()->getPosition());
    }
}
