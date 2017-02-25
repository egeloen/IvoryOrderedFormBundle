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

use Ivory\OrderedForm\Extension\OrderedButtonExtension;
use Ivory\OrderedForm\Extension\OrderedFormExtension;
use Ivory\OrderedForm\OrderedResolvedFormTypeFactory;
use Ivory\OrderedFormBundle\DependencyInjection\IvoryOrderedFormExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\FormType;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class IvoryOrderedFormExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerBuilder
     */
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

    public function testResolvedFormTypeFactory()
    {
        $this->container->compile();

        $this->assertInstanceOf(
            OrderedResolvedFormTypeFactory::class,
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

        if (!method_exists(AbstractType::class, 'getBlockPrefix')) {
            $this->assertSame([['alias' => 'form']], $services[$formExtension]);
            $this->assertSame([['alias' => 'button']], $services[$buttonExtension]);
        } else {
            $this->assertSame(
                [[
                    'extended_type' => FormType::class,
                    'extended-type' => FormType::class,
                ]],
                $services[$formExtension]
            );

            $this->assertSame(
                [[
                    'extended_type' => ButtonType::class,
                    'extended-type' => ButtonType::class,
                ]],
                $services[$buttonExtension]
            );
        }

        $this->assertInstanceOf(OrderedFormExtension::class, $this->container->get($formExtension));
        $this->assertInstanceOf(OrderedButtonExtension::class, $this->container->get($buttonExtension));
    }
}
