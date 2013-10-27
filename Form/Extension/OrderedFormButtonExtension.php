<?php

/*
* This file is part of the Ivory Ordered Form bundle package.
*
* (c) Eric GELOEN <geloen.eric@gmail.com>
*
* For the full copyright and license information, please read the LICENSE
* file that was distributed with this source code.
*/

namespace Ivory\OrderedFormBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
* Ordered form extension.
*
* @author tweini <tweini@gmail.com>
*/
class OrderedFormButtonExtension extends OrderedFormExtension
{
    /**
    * {@inheritdoc}
    */
    public function getExtendedType()
    {
        return 'button';
    }
}
