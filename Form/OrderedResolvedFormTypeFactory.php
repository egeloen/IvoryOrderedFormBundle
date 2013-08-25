<?php

/*
 * This file is part of the Ivory Ordered Form bundle package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\OrderedFormBundle\Form;

use Ivory\OrderedFormBundle\Form\Orderer\FormOrdererFactoryInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\ResolvedFormTypeFactory;
use Symfony\Component\Form\ResolvedFormTypeInterface;

/**
 * Ordered resolved form type factory.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class OrderedResolvedFormTypeFactory extends ResolvedFormTypeFactory
{
    /** @var \Ivory\OrderedFormBundle\Form\Orderer\FormOrdererFactoryInterface */
    protected $ordererFactory;

    /**
     * Creates an orderer resolved form type factory.
     *
     * @param \Ivory\OrderedFormBundle\Form\Orderer\FormOrdererFactoryInterface $ordererFactory The form orderer factory.
     */
    public function __construct(FormOrdererFactoryInterface $ordererFactory)
    {
        $this->ordererFactory = $ordererFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function createResolvedType(
        FormTypeInterface $type,
        array $typeExtensions,
        ResolvedFormTypeInterface $parent = null
    ) {
        return new OrderedResolvedFormType($this->ordererFactory->create(), $type, $typeExtensions, $parent);
    }
}
