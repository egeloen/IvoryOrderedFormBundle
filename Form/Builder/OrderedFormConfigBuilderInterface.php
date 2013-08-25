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

use Symfony\Component\Form\FormConfigBuilderInterface;

/**
 * Ordered form configuration builder.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
interface OrderedFormConfigBuilderInterface extends FormConfigBuilderInterface
{
    /**
     * Sets the form position.
     *
     *  * The position can be `null` to reflect the original forms order.
     *
     *  * The position can be `first` to place this form at the first position.
     *    If many forms are defined as `first`, the original order between these forms is maintained.
     *    Warning, `first` does not mean "very first" if there are many forms which are defined as `first`
     *    or if you set up an other form `before` this form.
     *
     *  * The position can be `last` to place this form at the last position.
     *    If many forms are defined as `last`, the original order between these forms is maintained.
     *    Warning, `last` does not mean "very last" if there are many forms which are defined as `last`
     *    or if you set up an other form `after` this form.
     *
     *  * The position can be `array('before' => 'form_name')` to place this form before the `form_name` form.
     *    If many forms defines the same `before` form, the original order between these forms is maintained.
     *    Warning, `before` does not mean "just before" if there are many forms which defined the same `before` form.
     *
     *  * The position can be `array('after' => 'form_name')` to place this form after the `form_name` form.
     *    If many forms defines the same after form, the original order between these forms is maintained.
     *    Warning, `after` does not mean "just after" if there are many forms which defined the same `after` form.
     *
     * You can combine the `after` & `before` options together or with `first` and/or `last` to achieve
     * more complex use cases.
     *
     * @param null|string|array $position The form position.
     *
     * @throws \Symfony\Component\Form\Exception\InvalidConfigurationException If the position is not valid.
     *
     * @return \Ivory\OrderedFormBundle\Form\OrderedFormConfigBuilderInterface The ordered form configuration builder.
     */
    public function setPosition($position);
}
