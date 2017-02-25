<?php

/*
 * This file is part of the Ivory Ordered Form bundle package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\OrderedFormBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class IvoryOrderedFormExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        if (!method_exists(AbstractType::class, 'getBlockPrefix')) {
            $container->getDefinition('ivory_ordered_form.form_extension')
                ->clearTag($tag = 'form.type_extension')
                ->addTag($tag, ['alias' => 'form']);

            $container->getDefinition('ivory_ordered_form.button_extension')
                ->clearTag($tag)
                ->addTag($tag, ['alias' => 'button']);
        }
    }
}
