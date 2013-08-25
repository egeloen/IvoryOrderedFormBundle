<?php

/*
 * This file is part of the Ivory Ordered Form bundle package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\OrderedFormBundle\Form\Orderer;

use Symfony\Component\Form\FormInterface;

/**
 * Form orderer.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
interface FormOrdererInterface
{
    /**
     * Orders the form.
     *
     * @param \Symfony\Component\Form\FormInterface $form The form.
     *
     * @return array The ordered form child names.
     */
    public function order(FormInterface $form);
}
