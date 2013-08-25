<?php

/*
 * This file is part of the Ivory Ordered Form bundle package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\OrderedFormBundle\Form\Builder;

use Ivory\OrderedFormBundle\Form\Exception\OrderedConfigurationException;
use Ivory\OrderedFormBundle\Form\OrderedFormConfigInterface;
use Symfony\Component\Form\SubmitButtonBuilder;
use Symfony\Component\Form\Exception\BadMethodCallException;

/**
 * Ordered submit button builder.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class OrderedSubmitButtonBuilder extends SubmitButtonBuilder implements OrderedFormConfigBuilderInterface, OrderedFormConfigInterface
{
    /** @var null|string|array */
    protected $position;

    /**
     * {@inheritdoc}
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function setPosition($position)
    {
        if ($this->locked) {
            throw new BadMethodCallException('The config builder cannot be modified anymore.');
        }

        if (is_string($position) && ($position !== 'first') && ($position !== 'last')) {
            throw OrderedConfigurationException::createInvalidStringPosition($this->getName(), $position);
        }

        if (is_array($position) && !isset($position['before']) && !isset($position['after'])) {
            throw OrderedConfigurationException::createInvalidArrayPosition($this->getName(), $position);
        }

        $this->position = $position;

        return $this;
    }
}
