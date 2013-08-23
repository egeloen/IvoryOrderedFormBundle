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

use Ivory\OrderedFormBundle\Form\Exception\OrderedConfigurationException;
use Symfony\Component\Form\FormInterface;

/**
 * Form orderer.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class FormOrderer implements FormOrdererInterface
{
    /** @var \Symfony\Component\Form\FormInterface */
    protected $form;

    /** @var array */
    protected $weights;

    /** @var array */
    protected $differed;

    /** @var integer */
    protected $firstWeight;

    /** @var integer */
    protected $currentWeight;

    /** @var integer */
    protected $lastWeight;

    /**
     * {@inheritdoc}
     */
    public function order(FormInterface $form)
    {
        $this->reset($form);

        foreach ($this->form as $child) {
            $position = $child->getConfig()->getPosition();

            if (empty($position)) {
                $this->processEmptyPosition($child);
            } else if (is_string($position)) {
                $this->processStringPosition($child, $position);
            } else {
                $this->processArrayPosition($child, $position);
            }
        }

        asort($this->weights, SORT_NUMERIC);

        return array_keys($this->weights);
    }

    /**
     * Processes an an empty position.
     *
     * @param \Symfony\Component\Form\FormInterface $form The form.
     */
    protected function processEmptyPosition(FormInterface $form)
    {
        $this->processWeight($form, $this->currentWeight);
    }

    /**
     * Processes a string position.
     *
     * @param \Symfony\Component\Form\FormInterface $form     The form.
     * @param string                                $position The position.
     */
    protected function processStringPosition(FormInterface $form, $position)
    {
        if ($position === 'first') {
            $this->processFirst($form);
        } else {
            $this->processLast($form);
        }
    }

    /**
     * Processes an array position.
     *
     * @param \Symfony\Component\Form\FormInterface $form     The form.
     * @param array                                 $position The position.
     */
    protected function processArrayPosition(FormInterface $form, array $position)
    {
        if (isset($position['before'])) {
            $this->processBefore($form, $position['before']);
        }

        if (isset($position['after'])) {
            $this->processAfter($form, $position['after']);
        }
    }

    /**
     * Processes a first position.
     *
     * @param \Symfony\Component\Form\FormInterface $form The form.
     */
    protected function processFirst(FormInterface $form)
    {
        $this->processWeight($form, $this->firstWeight++);
    }

    /**
     * Processes a last position.
     *
     * @param \Symfony\Component\Form\FormInterface $form The form.
     */
    protected function processLast(FormInterface $form)
    {
        $this->processWeight($form, $this->lastWeight + 1);
    }

    /**
     * Processes a before position.
     *
     * @param \Symfony\Component\Form\FormInterface $form   The form.
     * @param string                                $before The before form name.
     */
    protected function processBefore(FormInterface $form, $before)
    {
        if (!isset($this->weights[$before])) {
            $this->processDiffered($form, $before, 'before');
        } else {
            $this->processWeight($form, $this->weights[$before]);
        }
    }

    /**
     * Processes an after position.
     *
     * @param \Symfony\Component\Form\FormInterface $form  The form.
     * @param string                                $after The after form name.
     */
    protected function processAfter(FormInterface $form, $after)
    {
        if (!isset($this->weights[$after])) {
            $this->processDiffered($form, $after, 'after');
        } else {
            $this->processWeight($form, $this->weights[$after] + 1);
        }
    }

    /**
     * Processes a weight.
     *
     * @param \Symfony\Component\Form\FormInterface $form   The form.
     * @param integer                               $weight The weight.
     */
    protected function processWeight(FormInterface $form, $weight)
    {
        foreach ($this->weights as &$weightRef) {
            if ($weightRef >= $weight) {
                $weightRef++;
            }
        }

        if ($this->currentWeight >= $weight) {
            $this->currentWeight++;
        }

        $this->lastWeight++;

        $this->weights[$form->getName()] = $weight;
        $this->finishWeight($form, $weight);
    }

    /**
     * Finishes the weight processing.
     *
     * @param \Symfony\Component\Form\FormInterface $form     The form.
     * @param integer                               $weight   The weight.
     * @param string                                $position The position (null|before|after).
     *
     * @return integer The new weight.
     */
    protected function finishWeight(FormInterface $form, $weight, $position = null)
    {
        if ($position === null) {
            foreach (array_keys($this->differed) as $position) {
                $weight = $this->finishWeight($form, $weight, $position);
            }
        } else {
            $name = $form->getName();

            if (isset($this->differed[$position][$name])) {
                $postIncrement = $position === 'before';

                foreach ($this->differed[$position][$name] as $differed) {
                    $this->processWeight($differed, $postIncrement ? $weight++ : ++$weight);
                }

                unset($this->differed[$position][$name]);
            }
        }

        return $weight;
    }

    /**
     * Processes differed.
     *
     * @param \Symfony\Component\Form\FormInterface $form     The form.
     * @param string                                $differed The differed form name.
     * @param string                                $position The position (before|after).
     *
     * @throws \Ivory\OrderedFormBundle\Form\Exception\OrderedConfigurationException If the differed form does not exist.
     */
    protected function processDiffered(FormInterface $form, $differed, $position)
    {
        if (!$this->form->has($differed)) {
            throw OrderedConfigurationException::createInvalidDiffered($form->getName(), $position, $differed);
        }

        $this->differed[$position][$differed][] = $form;

        $name = $form->getName();

        $this->detectCircularDiffered($name, $position);
        $this->detectedSymetricDiffered($name, $differed, $position);
    }

    /**
     * Detects circular before/after differed.
     *
     * @param string $name     The form name.
     * @param string $position The position (before|after)
     * @param array  $stack    The circular stack.
     *
     * @throws \Ivory\OrderedFormBundle\Form\Exception\CircularConfigurationException If there is a circular before/after differed.
     */
    protected function detectCircularDiffered($name, $position, array $stack = array())
    {
        if (!isset($this->differed[$position][$name])) {
            return;
        }

        $stack[] = $name;

        foreach ($this->differed[$position][$name] as $differed) {
            $differedName = $differed->getName();

            if ($differedName === $stack[0]) {
                throw OrderedConfigurationException::createCircularDiffered($stack, $position);
            }

            $this->detectCircularDiffered($differedName, $position, $stack);
        }
    }

    /**
     * Detects symetric before/after differed.
     *
     * @param string $name     The form name.
     * @param string $differed The differed form name.
     * @param string $position The position (before|after).
     *
     * @throws \Ivory\OrderedFormBundle\Form\Exception\OrderedConfigurationException If there is a symetric before/after differed.
     */
    protected function detectedSymetricDiffered($name, $differed, $position)
    {
        $reversePosition = ($position === 'before') ? 'after' : 'before';

        if (isset($this->differed[$reversePosition][$name])) {
            foreach ($this->differed[$reversePosition][$name] as $diff) {
               if ($diff->getName() === $differed) {
                    throw OrderedConfigurationException::createSymetricDiffered($name, $differed);
                }
            }
        }
    }

    /**
     * Resets the orderer.
     *
     * @param \Symfony\Component\Form\FormInterface $form The form.
     */
    protected function reset(FormInterface $form)
    {
        $this->form = $form;

        $this->weights = array();
        $this->differed = array(
            'before' => array(),
            'after'  => array(),
        );

        $this->firstWeight = 0;
        $this->currentWeight = 0;
        $this->lastWeight = 0;
    }
}
