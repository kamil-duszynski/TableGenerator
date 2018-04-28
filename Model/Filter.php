<?php

namespace KamilDuszynski\TableGeneratorBundle\Model;

class Filter
{
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
     * @param string $name
     * @param string $value
     * @param string $label
     * @param string $operation
     * @param bool   $isAutoComplete
     */
    public function __construct($name, $label, $value = null, $operation = '=', $isAutoComplete = false)
    {
        $this->name           = $name;
        $this->label          = $label;
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
