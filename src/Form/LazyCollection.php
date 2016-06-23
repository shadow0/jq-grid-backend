<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 20.06.16
 * Time: 0:04
 */

namespace JqGridBackend\Form;

use Traversable;
use Zend\Form;
use Zend\Form\Element;
use Zend\Form\FormInterface;
use Zend\Form\ElementInterface;
use Zend\Form\FieldsetInterface;

class LazyCollection extends Element\Collection
{
    /**
     * Set the target element
     *
     * @param Form\ElementInterface|array|Traversable $elementOrFieldset
     * @return Element\Collection
     * @throws \Zend\Form\Exception\InvalidArgumentException
     */
    public function setTargetElement($elementOrFieldset)
    {

        $this->targetElement = $elementOrFieldset;

        return $this;
    }

    /**
     * Get target element
     *
     * @return Form\ElementInterface|null
     */
    public function getTargetElement()
    {
        $elementOrFieldset = $this->targetElement;
        if (is_array($elementOrFieldset)
            || ($elementOrFieldset instanceof Traversable && !$elementOrFieldset instanceof Form\ElementInterface)
        ) {
            $factory = $this->getFormFactory();
            $elementOrFieldset = $factory->create($elementOrFieldset);
        }

        if (!$elementOrFieldset instanceof Form\ElementInterface) {
            throw new Form\Exception\InvalidArgumentException(sprintf(
                '%s requires that $elementOrFieldset be an object implementing %s; received "%s"',
                __METHOD__,
                __NAMESPACE__ . '\ElementInterface',
                (is_object($elementOrFieldset) ? get_class($elementOrFieldset) : gettype($elementOrFieldset))
            ));
        }
        return $elementOrFieldset;
    }

    /**
     * Prepare the collection by adding a dummy template element if the user want one
     *
     * @param  FormInterface $form
     * @return mixed|void
     */
    public function prepareElement(FormInterface $form)
    {
        if (true === $this->shouldCreateChildrenOnPrepareElement) {
            if ($this->targetElement !== null && $this->count > 0) {
                while ($this->count > $this->lastChildIndex + 1) {
                    $this->addNewTargetElementInstance(++$this->lastChildIndex);
                }
            }
        }

        // Create a template that will also be prepared
        if ($this->shouldCreateTemplate) {
            $templateElement = $this->getTemplateElement();
            $this->add($templateElement);
        }

        parent::prepareElement($form);

        // The template element has been prepared, but we don't want it to be
        // rendered nor validated, so remove it from the list.
        if ($this->shouldCreateTemplate) {
            $this->remove($this->templatePlaceholder);
        }
    }

    /**
     * @return array
     * @throws \Zend\Form\Exception\InvalidArgumentException
     * @throws \Zend\Stdlib\Exception\InvalidArgumentException
     * @throws \Zend\Form\Exception\DomainException
     * @throws \Zend\Form\Exception\InvalidElementException
     */
    public function extract()
    {
        if ($this->object instanceof Traversable) {
            $this->object = ArrayUtils::iteratorToArray($this->object, false);
        }

        if (!is_array($this->object)) {
            return [];
        }

        $values = [];

        foreach ($this->object as $key => $value) {
            // If a hydrator is provided, our work here is done
            if ($this->hydrator) {
                $values[$key] = $this->hydrator->extract($value);
                continue;
            }

            $targetElement = $this->getTargetElement();
            // If the target element is a fieldset that can accept the provided value
            // we should clone it, inject the value and extract the data
            //TODO
            if ($targetElement instanceof FieldsetInterface) {
                if (! $targetElement->allowObjectBinding($value)) {
                    continue;
                }
                //$targetElement = clone $this->targetElement;
                $targetElement->setObject($value);
                $values[$key] = $targetElement->extract();
                if (!$this->createNewObjects() && $this->has($key)) {
                    $this->get($key)->setObject($value);
                }
                continue;
            }

            // If the target element is a non-fieldset element, just use the value
            if ($targetElement instanceof ElementInterface) {
                $values[$key] = $value;
                if (!$this->createNewObjects() && $this->has($key)) {
                    $this->get($key)->setValue($value);
                }
                continue;
            }
        }

        return $values;
    }

    /**
     * Create a new instance of the target element
     *
     * @return ElementInterface
     */
    protected function createNewTargetElementInstance()
    {
        return $this->getTargetElement();
    }
}