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

use Symfony\Component\Form\FormConfigInterface;

/**
 * Ordered form configuration.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
interface OrderedFormConfigInterface extends FormConfigInterface
{
    /**
     * Gets the form position.
     *
     * @see \Ivory\OrderedFormBundle\Form\OrderedFormConfigBuilderInterface::setPosition
     *
     * @return null|string|array The form position.
     */
    public function getPosition();
}
