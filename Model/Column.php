<?php

namespace KamilDuszynski\TableGeneratorBundle\Model;

class Column
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
    private $route;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var array
     */
    private $attr;

    /**
     * @var
     */
    private $valueDecorator;

    /**
     * @param string $name
     * @param string $label
     * @param string $route
     * @param array  $parameters
     * @param array  $attr
     * @param $valueDecorator
     */
    public function __construct($name, $label, $route = null, $parameters = [], $attr = [], $valueDecorator = null)
    {
        $this->name           = $name;
        $this->label          = $label;
        $this->route          = $route;
        $this->parameters     = $parameters;
        $this->attr           = $attr;
        $this->valueDecorator = $valueDecorator;
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
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return array
     */
    public function getAttr()
    {
        return $this->attr;
    }

    /**
     * @return mixed
     */
    public function getValueDecorator()
    {
        return $this->valueDecorator;
    }
}