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

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\ButtonTypeInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\ResolvedFormType;
use Symfony\Component\Form\SubmitButtonTypeInterface;

/**
 * Ordered resolved form type.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class OrderedResolvedFormType extends ResolvedFormType
{
    /**
     * {@inheritdoc}
     */
    public function createView(FormInterface $form, FormView $parent = null)
    {
        $options = $form->getConfig()->getOptions();
        $view = $this->newView($parent);
        $this->buildView($view, $form, $options);

        foreach ($this->getOrderedFormChilds($form) as $name) {
            $view->children[$name] = $form[$name]->createView($view);
        }

        $this->finishView($view, $form, $options);

        return $view;
    }

    /**
     * {@inheritdoc}
     */
    protected function newBuilder($name, $dataClass, FormFactoryInterface $factory, array $options)
    {
        $innerType = $this->getInnerType();

        if ($innerType instanceof ButtonTypeInterface) {
            return new OrderedButtonBuilder($name, $options);
        }

        if ($innerType instanceof SubmitButtonTypeInterface) {
            return new OrderedSubmitButtonBuilder($name, $options);
        }

        return new OrderedFormBuilder($name, $dataClass, new EventDispatcher(), $factory, $options);
    }

    /**
     * Returns the ordered childs form name of a form.
     *
     * @param \Symfony\Component\Form\FormInterface $form The form.
     *
     * @return array The ordered childs form name.
     */
    protected function getOrderedFormChilds(FormInterface $form)
    {
        $cachedPositions = array();

        $weights = array();
        $afterGapWeights = array();
        $firstFormWeight = 0;
        $lastFormWeight = 0;

        $differredBefores = array();
        $differredAfters = array();

        /* @var FormInterface $child */
        foreach ($form as $child) {
            $position = $child->getConfig()->getPosition();

            if (is_string($position)) {
                if ($position === 'first') {
                    if (isset($differredBefores[$child->getName()])) {
                        foreach ($differredBefores[$child->getName()] as $differredBefore) {
                            $weights = $this->incrementWeights($weights, $firstFormWeight);
                            $weights[$differredBefore] = $firstFormWeight++;
                            $lastFormWeight++;
                        }
                    }

                    $weights = $this->incrementWeights($weights, $firstFormWeight);
                    $weights[$child->getName()] = $firstFormWeight++;
                    $lastFormWeight++;

                    if (isset($differredAfters[$child->getName()])) {
                        foreach ($differredAfters[$child->getName()] as $differredAfter) {
                            $weights = $this->incrementWeights($weights, $firstFormWeight);
                            $weights[$differredAfter] = $firstFormWeight++;
                            $lastFormWeight++;
                        }
                    }
                } else {
                    if (isset($differredBefores[$child->getName()])) {
                        foreach ($differredBefores[$child->getName()] as $differredBefore) {
                            $weights[$differredBefore] = empty($weights) ? 0 : max($weights) + 1;
                            $lastFormWeight++;
                        }
                    }

                    $weights[$child->getName()] = empty($weights) ? 0 : max($weights) + 1;

                    if (isset($differredAfters[$child->getName()])) {
                        foreach ($differredAfters[$child->getName()] as $differredAfter) {
                            $weights[$differredAfter] = empty($weights) ? 0 : max($weights) + 1;
                            $lastFormWeight++;
                        }
                    }
                }

                continue;
            } elseif (is_array($position)) {
                if (isset($position['before'])) {
                    $cachedPositions[$child->getName()]['before'] = $position['before'];

                    if (!isset($cachedPositions[$position['before']]['after'])) {
                        $cachedPositions[$position['before']]['after'] = $child->getName();
                    }
                } elseif (!isset($cachedPositions[$child->getName()]['before'])) {
                    $cachedPositions[$child->getName()]['before'] = null;
                }

                if (isset($position['after'])) {
                    $cachedPositions[$child->getName()]['after'] = $position['after'];

                    if (!isset($cachedPositions[$position['after']]['before'])) {
                        $cachedPositions[$position['after']]['before'] = $child->getName();
                    }
                } elseif (!isset($cachedPositions[$child->getName()]['after'])) {
                    $cachedPositions[$child->getName()]['after'] = null;
                }

                $before = $cachedPositions[$child->getName()]['before'];
                if ($before !== null) {
                    $this->detectCircularBeforeAndAfterReferences($cachedPositions, $before, $child->getName());

                    if (isset($weights[$before])) {
                        $beforeOrder = $weights[$before];
                        $weights = $this->incrementWeights($weights, $weights[$before]);
                        $weights[$child->getName()] = $beforeOrder;
                        $lastFormWeight++;
                    } else {
                        if (isset($differredBefores[$before])) {
                            $differredBefores[$before][] = $child->getName();
                        } else {
                            $differredBefores[$before] = array($child->getName());
                        }
                    }

                    continue;
                }

                $after = $cachedPositions[$child->getName()]['after'];
                if ($after !== null) {
                    if (isset($weights[$after])) {
                        if (!isset($afterGapWeights[$after])) {
                            $afterGapWeights[$after] = 0;
                        }

                        $newOrder = $weights[$after] + $afterGapWeights[$after] + 1;
                        $weights = $this->incrementWeights($weights, $newOrder);
                        $weights[$child->getName()] = $newOrder;
                        $lastFormWeight++;

                        $afterGapWeights[$after]++;
                    } else {
                        if (isset($differredAfters[$after])) {
                            $differredAfters[$after][] = $child->getName();
                        } else {
                            $differredAfters[$after] = array($child->getName());
                        }
                    }

                    continue;
                }
            }

            if (isset($differredBefores[$child->getName()])) {
                foreach ($differredBefores[$child->getName()] as $differredBefore) {
                    $weights = $this->incrementWeights($weights, $lastFormWeight);
                    $weights[$differredBefore] = $lastFormWeight++;
                }
            }

            $weights = $this->incrementWeights($weights, $lastFormWeight);
            $weights[$child->getName()] = $lastFormWeight++;

            if (isset($differredAfters[$child->getName()])) {
                foreach ($differredAfters[$child->getName()] as $differredAfter) {
                    $weights = $this->incrementWeights($weights, $lastFormWeight);
                    $weights[$differredAfter] = $lastFormWeight++;
                }
            }
        }

        asort($weights, SORT_NUMERIC);

        return array_keys($weights);
    }

    /**
     * Increments all fields weights greater than start.
     *
     * @param array   $weights The form weights.
     * @param integer $start   The start.
     *
     * @return array The form weights incremented.
     */
    protected function incrementWeights(array $weights, $start)
    {
        if (!empty($weights) && (max($weights) >= $start)) {
            foreach ($weights as &$weight) {
                if ($weight >= $start) {
                    $weight++;
                }
            }
        }

        return $weights;
    }

    /**
     * Detetects a circle before/after references.
     *
     * @param array  $positions The cached positions.
     * @param string $item      The checked item.
     * @param string $name      The original item name.
     *
     * @throws InvalidConfigurationException If there is a circular before/after references.
     */
    protected function detectCircularBeforeAndAfterReferences(array $positions, $item, $name)
    {
        if (isset($positions[$item]['before'])) {
            if ($positions[$item]['before'] === $name) {
                throw new InvalidConfigurationException(sprintf(
                    'The form ordering cannot be resolved due to conflict in after/before options. '.
                    'The field "%s" can not have "%s" as before field if the field "%s" have "%s" as before field.',
                    $name,
                    $item,
                    $item,
                    $name
                ));
            }

            $this->detectCircularBeforeAndAfterReferences($positions, $positions[$item]['before'], $name);
        }
    }
}
