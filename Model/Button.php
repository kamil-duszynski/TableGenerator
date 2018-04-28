<?php

namespace KamilDuszynski\TableGeneratorBundle\Model;

class Button
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string|null
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
     * @var null|string
     */
    private $icon;

    /**
     * @var null|string
     */
    private $modalMessage;

    /**
     * @param string      $name
     * @param string|null $label
     * @param string      $route
     * @param array       $parameters
     * @param array       $attr
     * @param string|null $icon
     * @param string|null $modalMessage
     */
    public function __construct($name, $label = null, $route, $parameters = [], $attr = [], $icon = null, $modalMessage = null)
    {
        $this->name         = $name;
        $this->label        = $label;
        $this->icon         = $icon;
        $this->modalMessage = $modalMessage;
        $this->route        = $route;
        $this->parameters   = $parameters;
        $this->attr         = $attr;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return null|string
     */
    public function getIcon()
    {
        return $this->icon;
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
     * @return string|null
     */
    public function getModalMessage()
    {
        return $this->modalMessage;
    }
}
