<?php

namespace KamilDuszynski\TableGeneratorBundle\Model;

class Filter
{
    /**
     * @const string
     */
    const TYPE_INPUT = 'input';

    /**
     * @const string
     */
    const TYPE_CHECKBOX = 'checkbox';

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $type;

    /**
     * @var null|string
     */
    private $property;

    /**
     * @var string
     */
    private $value;

    /**
     * @var string
     */
    private $operation;

    /**
     * @var bool
     */
    private $isAutoComplete;

    /**
     * @param string      $name
     * @param string      $label
     * @param string      $type
     * @param string|null $property
     * @param string|null $value
     * @param string      $operation
     * @param bool        $isAutoComplete
     */
    public function __construct(
        $name,
        $label,
        $type = self::TYPE_INPUT,
        $property = null,
        $value = null,
        $operation = '=',
        $isAutoComplete = false
    ) {
        $this->name           = $name;
        $this->label          = $label;
        $this->type           = $type;
        $this->property       = $property;
        $this->value          = $value;
        $this->operation      = $operation;
        $this->isAutoComplete = $isAutoComplete;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return null|string
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * @return bool
     */
    public function isAutoComplete()
    {
        return $this->isAutoComplete;
    }
}
