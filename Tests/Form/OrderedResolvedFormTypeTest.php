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

use Ivory\OrderedFormBundle\Form\Builder\OrderedFormBuilder;
use Ivory\OrderedFormBundle\Form\Orderer\FormOrderer;
use Ivory\OrderedFormBundle\Form\OrderedResolvedFormType;
use Symfony\Component\Form\FormView;

/**
 * Ordered resolved form type test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class OrderedResolvedFormTypeTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Symfony\Component\EventDispatcher\EventDispatcher */
    protected $dispatcher;

    /** @var \Symfony\Component\Form\FormFactoryInterface */
    protected $factory;

    /** @var \Symfony\Component\Form\DataMapperInterface */
    protected $dataMapper;

    /** @var \Ivory\OrderedFormBundle\Form\OrderedResolvedFormType */
    protected $type;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->factory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');
        $this->dataMapper = $this->getMock('Symfony\Component\Form\DataMapperInterface');

        $this->type = new OrderedResolvedFormType(
            new FormOrderer(),
            $this->createMockFormType(),
            array(),
            new OrderedResolvedFormType(new FormOrderer(), $this->createMockFormType())
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->dispatcher);
        unset($this->factory);
        unset($this->dataMapper);
        unset($this->type);
    }

    /**
     * Gets the valid positions.
     *
     * @return array The valid positions.
     */
    public function getValidPositions()
    {
        return array(
            // No position
            array(
                array('foo', 'bar', 'baz', 'bat'),
                array('foo', 'bar', 'baz', 'bat'),
            ),

            // First position
            array(
                array('foo' => 'first', 'bar', 'baz', 'bat'),
                array('foo', 'bar', 'baz', 'bat'),
            ),
            array(
                array('bar', 'baz', 'foo' => 'first', 'bat'),
                array('foo', 'bar', 'baz', 'bat'),
            ),
            array(
                array('bar', 'baz', 'bat', 'foo' => 'first'),
                array('foo', 'bar', 'baz', 'bat'),
            ),
            array(
                array('baz', 'foo' => 'first', 'bat', 'bar' => 'first'),
                array('foo', 'bar', 'baz', 'bat'),
            ),

            // Last position
            array(
                array('foo', 'bar', 'baz', 'bat' => 'last'),
                array('foo', 'bar', 'baz', 'bat'),
            ),
            array(
                array('foo', 'bar', 'bat' => 'last', 'baz'),
                array('foo', 'bar', 'baz', 'bat'),
            ),
            array(
                array('bat' => 'last', 'foo', 'bar', 'baz'),
                array('foo', 'bar', 'baz', 'bat'),
            ),
            array(
                array('baz' => 'last', 'foo', 'bat' => 'last', 'bar'),
                array('foo', 'bar', 'baz', 'bat'),
            ),

            // Before position
            array(
                array('foo' => array('before' => 'bar'), 'bar', 'baz', 'bat'),
                array('foo', 'bar', 'baz', 'bat'),
            ),
            array(
                array('bar', 'foo' => array('before' => 'bar'), 'baz', 'bat'),
                array('foo', 'bar', 'baz', 'bat'),
            ),
            array(
                array('bar', 'baz', 'bat', 'foo' => array('before' => 'bar')),
                array('foo', 'bar', 'baz', 'bat'),
            ),
            array(
                array(
                    'bar' => array('before' => 'baz'),
                    'foo' => array('before' => 'bar'),
                    'bat',
                    'baz' => array('before' => 'bat'),
                ),
                array('foo', 'bar', 'baz', 'bat'),
            ),
            array(
                array(
                    'bar' => array('before' => 'bat'),
                    'foo' => array('before' => 'bar'),
                    'bat',
                    'baz' => array('before' => 'bat'),
                ),
                array('foo', 'bar', 'baz', 'bat'),
            ),

            // After position
            array(
                array('foo', 'bar' => array('after' => 'foo'), 'baz', 'bat'),
                array('foo', 'bar', 'baz', 'bat'),
            ),
            array(
                array('bar' => array('after' => 'foo'), 'foo', 'baz', 'bat'),
                array('foo', 'bar', 'baz', 'bat'),
            ),
            array(
                array('foo', 'baz', 'bat', 'bar' => array('after' => 'foo')),
                array('foo', 'bar', 'baz', 'bat'),
            ),
            array(
                array(
                    'foo',
                    'baz' => array('after' => 'bar'),
                    'bat' => array('after' => 'baz'),
                    'bar' => array('after' => 'foo'),
                ),
                array('foo', 'bar', 'baz', 'bat'),
            ),
            array(
                array(
                    'foo',
                    'baz' => array('after' => 'bar'),
                    'bat' => array('after' => 'bar'),
                    'bar' => array('after' => 'foo'),
                ),
                array('foo', 'bar', 'baz', 'bat'),
            ),

            // First & last position
            array(
                array('foo' => 'first', 'bar', 'baz', 'bat' => 'last'),
                array('foo', 'bar', 'baz', 'bat'),
            ),
            array(
                array('bar', 'bat' => 'last', 'foo' => 'first', 'baz'),
                array('foo', 'bar', 'baz', 'bat'),
            ),
            array(
                array('baz' => 'last', 'foo' => 'first', 'bar' => 'first', 'bat' => 'last'),
                array('foo', 'bar', 'baz', 'bat'),
            ),

            // Before & after position
            array(
                array('foo', 'bar' => array('after' => 'foo', 'before' => 'baz'), 'baz', 'bat'),
                array('foo', 'bar', 'baz', 'bat'),
            ),
            array(
                array('foo', 'bar' => array('before' => 'baz', 'after' => 'foo'), 'baz', 'bat'),
                array('foo', 'bar', 'baz', 'bat'),
            ),
            array(
                array('bar' => array('after' => 'foo', 'before' => 'baz'), 'foo', 'baz', 'bat'),
                array('foo', 'bar', 'baz', 'bat'),
            ),
            array(
                array('bar' => array('before' => 'baz', 'after' => 'foo'), 'foo', 'baz', 'bat'),
                array('foo', 'bar', 'baz', 'bat'),
            ),
            array(
                array('foo', 'baz', 'bat', 'bar' => array('after' => 'foo', 'before' => 'baz')),
                array('foo', 'bar', 'baz', 'bat'),
            ),
            array(
                array('foo', 'baz', 'bat', 'bar' => array('before' => 'baz', 'after' => 'foo')),
                array('foo', 'bar', 'baz', 'bat'),
            ),
            array(
                array('foo' => array('before' => 'bar'), 'bar', 'baz' => array('after' => 'bar'), 'bat'),
                array('foo', 'bar', 'baz', 'bat'),
            ),
            array(
                array('bar', 'foo' => array('before' => 'bar'), 'bat', 'baz' => array('after' => 'bar')),
                array('foo', 'bar', 'baz', 'bat'),
            ),
            array(
                array('bar' => array('after' => 'foo'), 'foo', 'bat', 'baz' => array('before' => 'bat')),
                array('foo', 'bar', 'baz', 'bat'),
            ),
            array(
                array(
                    'bar' => array('after' => 'foo', 'before' => 'baz'),
                    'foo',
                    'bat',
                    'baz' => array('before' => 'bat', 'after' => 'bar'),
                ),
                array('foo', 'bar', 'baz', 'bat'),
            ),

            // First, last, before & after position
            array(
                array(
                    'bar' => array('after' => 'foo', 'before' => 'baz'),
                    'foo' => 'first',
                    'bat' => 'last',
                    'baz' => array('before' => 'bat', 'after' => 'bar'),
                ),
                array('foo', 'bar', 'baz', 'bat'),
            ),
            array(
                array(
                    'bar' => array('after' => 'foo', 'before' => 'baz'),
                    'foo' => 'first',
                    'bat',
                    'baz' => array('before' => 'bat'),
                    'nan' => 'last',
                    'pop' => array('after' => 'ban'),
                    'ban',
                    'biz' => array('before' => 'nan'),
                    'boz' => array('before' => 'biz', array('after' => 'pop')),

                ),
                array('foo', 'bar', 'baz', 'bat', 'ban', 'pop', 'boz', 'biz', 'nan'),
            ),
        );
    }

    /**
     * Gets the invalid positions.
     *
     * @return array The invalid positions.
     */
    public function getInvalidPositions()
    {
        return array(
            // Invalid before/after
            array(
                array('foo' => array('before' => 'bar')),
                'The "foo" form is configured to be placed just before the form "bar" but the form "bar" does not exist.',
            ),
            array(
                array('foo' => array('after' => 'bar')),
                'The "foo" form is configured to be placed just after the form "bar" but the form "bar" does not exist.',
            ),

            // Circular before
            array(
                array('foo' => array('before' => 'foo')),
                'The form ordering cannot be resolved due to conflict in before positions ("foo" => "foo")',
            ),
            array(
                array('foo' => array('before' => 'bar'), 'bar' => array('before' => 'foo')),
                'The form ordering cannot be resolved due to conflict in before positions ("bar" => "foo" => "bar").',
            ),
            array(
                array(
                    'foo' => array('before' => 'bar'),
                    'bar' => array('before' => 'baz'),
                    'baz' => array('before' => 'foo'),
                ),
                'The form ordering cannot be resolved due to conflict in before positions ("baz" => "bar" => "foo" => "baz").',
            ),

            // Circular after
            array(
                array('foo' => array('after' => 'foo')),
                'The form ordering cannot be resolved due to conflict in after positions ("foo" => "foo").',
            ),
            array(
                array('foo' => array('after' => 'bar'), 'bar' => array('after' => 'foo')),
                'The form ordering cannot be resolved due to conflict in after positions ("bar" => "foo" => "bar").',
            ),
            array(
                array(
                    'foo' => array('after' => 'bar'),
                    'bar' => array('after' => 'baz'),
                    'baz' => array('after' => 'foo'),
                ),
                'The form ordering cannot be resolved due to conflict in after positions ("baz" => "bar" => "foo" => "baz").',
            ),

            // Symetric before/after
            array(
                array('foo' => array('before' => 'bar'), 'bar' => array('after' => 'foo')),
                'The form ordering does not support symetrical before/after option ("bar" <=> "foo").',
            ),
            array(
                array(
                    'bat' => array('before' => 'baz'),
                    'baz' => array('after' => 'bar'),
                    'foo' => array('before' => 'bar'),
                    'bar' => array('after' => 'foo'),
                ),
                'The form ordering does not support symetrical before/after option ("bar" <=> "foo").',
            )
        );
    }

    /**
     * Tests valid position.
     *
     * @param array $config The form configuration order.
     * @param array $expected The expected order.
     *
     * @dataProvider getValidPositions
     */
    public function testValidPosition(array $config, array $expected)
    {
        $view = $this->type->createView($this->createForm($config), new FormView());
        $children = array_values($view->children);

        foreach ($expected as $index => $value) {
            $this->assertArrayHasKey($index, $children);
            $this->assertArrayHasKey($value, $view->children);

            $this->assertSame($children[$index], $view->children[$value]);
        }
    }

    /**
     * Tests invalid position.
     *
     * @param array  $config           The form configuration order.
     * @param string $exceptionMessage The expected exception message.
     *
     * @dataProvider getInvalidPositions
     */
    public function testInvalidPosition(array $config, $exceptionMessage = null)
    {
        $exceptionName = 'Ivory\OrderedFormBundle\Form\Exception\OrderedConfigurationException';

        if ($exceptionMessage !== null) {
            $this->setExpectedException($exceptionName, $exceptionMessage);
        } else {
            $this->setExpectedException($exceptionName);
        }

        $this->type->createView($this->createForm($config), new FormView());
    }

    public function testCreateBuilderWithButtonInnerType()
    {
        $innerType = $this->getMock('Symfony\Component\Form\ButtonTypeInterface');

        $this->type = new OrderedResolvedFormType(
            new FormOrderer(),
            $innerType,
            array(),
            new OrderedResolvedFormType(new FormOrderer(), $innerType)
        );

        $this->assertInstanceOf(
            'Ivory\OrderedFormBundle\Form\Builder\OrderedButtonBuilder',
            $this->type->createBuilder($this->createMockFormFactory(), 'name')
        );
    }

    public function testCreateBuilderWithSubmitButtonInnerType()
    {
        $innerType = $this->getMock('Symfony\Component\Form\SubmitButtonTypeInterface');

        $this->type = new OrderedResolvedFormType(
            new FormOrderer(),
            $innerType,
            array(),
            new OrderedResolvedFormType(new FormOrderer(), $innerType)
        );

        $this->assertInstanceOf(
            'Ivory\OrderedFormBundle\Form\Builder\OrderedSubmitButtonBuilder',
            $this->type->createBuilder($this->createMockFormFactory(), 'name')
        );
    }

    public function testCreateBuilderWithFormInnerType()
    {
        $innerType = $this->createMockFormType();

        $this->type = new OrderedResolvedFormType(
            new FormOrderer(),
            $innerType,
            array(),
            new OrderedResolvedFormType(new FormOrderer(), $innerType)
        );

        $this->assertInstanceOf(
            'Ivory\OrderedFormBundle\Form\Builder\OrderedFormBuilder',
            $this->type->createBuilder($this->createMockFormFactory(), 'name')
        );
    }

    /**
     * Creates a form.
     *
     * @param array $config The form configuration.
     *
     * @return \Symfony\Component\Form\FormInterface The form.
     */
    protected function createForm(array $config)
    {
        $builder = $this->createBuilder()
            ->setCompound(true)
            ->setDataMapper($this->dataMapper)
            ->setType($this->type);

        foreach ($config as $name => $value) {
            $fieldResolvedType = new OrderedResolvedFormType(new FormOrderer(), $this->createMockFormType());

            if ((is_string($value) && is_string($name)) || is_array($value)) {
                $builder->add($this->createBuilder($name)->setType($fieldResolvedType)->setPosition($value));
            } else {
                $builder->add($this->createBuilder($value)->setType($fieldResolvedType));
            }
        }

        return $builder->getForm();
    }

    /**
     * Creates a form type mock.
     *
     * @return \Symfony\Component\Form\FormTypeInterface The form type mock.
     */
    protected function createMockFormType()
    {
        return $this->getMock('Symfony\Component\Form\FormTypeInterface');
    }

    /**
     * Creates a form factory mock.
     *
     * @return \Symfony\Component\Form\FormFactoryInterface The form factory mock.
     */
    protected function createMockFormFactory()
    {
        return $this->getMock('Symfony\Component\Form\FormFactoryInterface');
    }

    /**
     * Creates an ordered form builder.
     *
     * @param string $name    The form name.
     * @param array  $options The form options.
     *
     * @return \Ivory\OrderedFormBundle\Form\Builder\OrderedFormBuilder The ordered form builder.
     */
    protected function createBuilder($name = 'name', array $options = array())
    {
        return new OrderedFormBuilder($name, null, $this->dispatcher, $this->factory, $options);
    }
}
