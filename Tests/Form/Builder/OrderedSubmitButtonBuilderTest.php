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

use Ivory\OrderedFormBundle\Form\Builder\OrderedSubmitButtonBuilder;

/**
 * Ordered submit button builder test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class OrderedSubmitButtonBuilderTest extends AbstractOrderedBuilderTest
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->builder = new OrderedSubmitButtonBuilder('foo', array());
    }
}
