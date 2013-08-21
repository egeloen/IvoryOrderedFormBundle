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

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Exception\InvalidConfigurationException;

/**
 * Ordered form builder.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class OrderedFormBuilder extends FormBuilder
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
        if (is_string($position) && ($position !== 'first') && ($position !== 'last')) {
            throw new InvalidConfigurationException(
                'If you use position as string, you can only use "first" & "last".'
            );
        }

        if (is_array($position) && !isset($position['before']) && !isset($position['after'])) {
            throw new InvalidConfigurationException(
                'If you use position as array, you must at least define the "before" or "after" option.'
            );
        }

        $this->position = $position;
    }
}
