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

/**
 * Ivory ordered form extension test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class IvoryOrderedFormExtensionTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Symfony\Component\DependencyInjection\ContainerBuilder */
    protected $container;

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

    public function testFormExtension()
    {
        $this->container->compile();

        $this->assertArrayHasKey(
            'ivory_ordered_form.form_extension',
            $this->container->findTaggedServiceIds('form.type_extension')
        );

        $this->assertInstanceOf(
            'Ivory\OrderedForm\Extension\OrderedFormExtension',
            $this->container->get('ivory_ordered_form.form_extension')
        );
    }

    public function testButtonExtension()
    {
        $this->container->compile();

        $this->assertArrayHasKey(
            'ivory_ordered_form.button_extension',
            $this->container->findTaggedServiceIds('form.type_extension')
        );

        $this->assertInstanceOf(
            'Ivory\OrderedForm\Extension\OrderedButtonExtension',
            $this->container->get('ivory_ordered_form.button_extension')
        );
    }
}
