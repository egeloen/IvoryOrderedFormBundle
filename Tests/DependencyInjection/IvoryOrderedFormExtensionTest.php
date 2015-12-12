<?php

/*
 * This file is part of the Ivory Ordered Form bundle package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\OrderedFormBundle\Tests\DependencyInjection;

use Ivory\OrderedFormBundle\DependencyInjection\IvoryOrderedFormExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Ivory ordered form extension test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class IvoryOrderedFormExtensionTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Symfony\Component\DependencyInjection\ContainerBuilder */
    private $container;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->container = new ContainerBuilder();
        $this->container->registerExtension($extension = new IvoryOrderedFormExtension());
        $this->container->loadFromExtension($extension->getAlias());
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->container);
    }

    public function testResolvedFormTypeFactory()
    {
        $this->container->compile();

        $this->assertInstanceOf(
            'Ivory\OrderedForm\OrderedResolvedFormTypeFactory',
            $this->container->get('form.resolved_type_factory')
        );
    }

    public function testFormExtensions()
    {
        $this->container->compile();

        $services = $this->container->findTaggedServiceIds($tag = 'form.type_extension');

        $this->assertCount(2, $services);
        $this->assertArrayHasKey($formExtension = 'ivory_ordered_form.form_extension', $services);
        $this->assertArrayHasKey($buttonExtension = 'ivory_ordered_form.button_extension', $services);

        if (Kernel::VERSION_ID < 20800) {
            $this->assertSame(array(array('alias' => 'form')), $services[$formExtension]);
            $this->assertSame(array(array('alias' => 'button')), $services[$buttonExtension]);
        } else {
            $this->assertSame(
                array(array(
                    'extended_type' => 'Symfony\Component\Form\Extension\Core\Type\FormType',
                    'extended-type' => 'Symfony\Component\Form\Extension\Core\Type\FormType'
                )),
                $services[$formExtension]
            );

            $this->assertSame(
                array(array(
                    'extended_type' => 'Symfony\Component\Form\Extension\Core\Type\ButtonType',
                    'extended-type' => 'Symfony\Component\Form\Extension\Core\Type\ButtonType'
                )),
                $services[$buttonExtension]
            );
        }

        $this->assertInstanceOf(
            'Ivory\OrderedForm\Extension\OrderedFormExtension',
            $this->container->get($formExtension)
        );

        $this->assertInstanceOf(
            'Ivory\OrderedForm\Extension\OrderedButtonExtension',
            $this->container->get($buttonExtension)
        );
    }
}
