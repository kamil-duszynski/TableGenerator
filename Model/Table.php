<?php

namespace KamilDuszynski\TableGeneratorBundle\Model;

class Table
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $class;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var array
     */
    private $data;

    /**
     * @var Pagination
     */
    private $pagination;

    /**
     * @var Filter[]
     */
    private $filters;

    /**
     * @var Column[]
     */
    private $columns;

    /**
     * @var Button[]
     */
    private $buttons;

    /**
     * @var Button[]
     */
    private $actionPanel;

    /**
     * @var bool
     */
    private $isOrdered;

    /**
     * @var bool
     */
    private $hasCheckboxes;

    /**
     * @var Export
     */
    private $export;

    /**
     * @param string     $name
     * @param string     $class
     * @param array      $parameters
     * @param array      $data
     * @param Pagination $pagination
     * @param Filter[]   $filters
     * @param Column[]   $columns
     * @param Button[]   $buttons
     * @param Button[]   $actionPanel
     * @param bool       $isOrdered
     * @param bool       $hasCheckboxes
     * @param Export     $export
     */
    public function __construct(
        $name,
        $class,
        $parameters = [],
        $data    = [],
        Pagination $pagination,
        $filters = [],
        $columns = [],
        $buttons = [],
        $actionPanel = [],
        $isOrdered = false,
        $hasCheckboxes = true,
        Export $export
    ) {
        $this->name          = $name;
        $this->class         = $class;
        $this->parameters    = $parameters;
        $this->data          = $data;
        $this->pagination    = $pagination;
        $this->filters       = $filters;
        $this->columns       = $columns;
        $this->buttons       = $buttons;
        $this->actionPanel   = $actionPanel;
        $this->isOrdered     = $isOrdered;
        $this->hasCheckboxes = $hasCheckboxes;
        $this->export        = $export;
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
    public function getClass()
    {
        return $this->class;
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
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return Pagination
     */
    public function getPagination()
    {
        return $this->pagination;
    }

    /**
     * @return Filter[]
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @return Column[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @return Button[]
     */
    public function getButtons()
    {
        return $this->buttons;
    }

    /**
     * @return Button[]
     */
    public function getActionPanel()
    {
        return $this->actionPanel;
    }

    /**
     * @return bool
     */
    public function isOrdered()
    {
        return $this->isOrdered;
    }

    /**
     * @return bool
     */
    public function hasCheckboxes()
    {
        return $this->hasCheckboxes;
    }

    /**
     * @return Export
     */
    public function getExport()
    {
        return $this->export;
    }
}