<?php

namespace KamilDuszynski\TableGeneratorBundle;

use Doctrine\ORM\EntityManager;
use KamilDuszynski\TableGeneratorBundle\Builder\ActionPanelBuilder;
use KamilDuszynski\TableGeneratorBundle\Builder\ButtonBuilder;
use KamilDuszynski\TableGeneratorBundle\Builder\ColumnBuilder;
use KamilDuszynski\TableGeneratorBundle\Builder\FilterBuilder;
use KamilDuszynski\TableGeneratorBundle\Helper\DataHelper;
use KamilDuszynski\TableGeneratorBundle\Helper\ExportHelper;
use KamilDuszynski\TableGeneratorBundle\Helper\PaginationHelper;
use KamilDuszynski\TableGeneratorBundle\Model\Button;
use KamilDuszynski\TableGeneratorBundle\Model\Export;
use KamilDuszynski\TableGeneratorBundle\Model\Filter;
use KamilDuszynski\TableGeneratorBundle\Model\Table;
use KamilDuszynski\TableGeneratorBundle\Table\TableTypeInterface;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TableGenerator
{
    const PARAMETER_TABLE_NAME           = 'name';
    const PARAMETER_TABLE_IS_ORDERED     = 'isOrdered';
    const PARAMETER_TABLE_HAS_CHECKBOXES = 'hasCheckboxes';

    /**
     * @var TableTypeInterface
     */
    private $tableType;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var null|\Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * @var TwigEngine
     */
    private $twigEngine;

    /**
     * @var array
     */
    private $data;

    /**
     * @var string|null
     */
    private $tableName;

    /**
     * @var PaginationHelper
     */
    private $pagination;

    /**
     * @var Export
     */
    private $export;

    /**
     * @param RequestStack     $requestStack
     * @param TwigEngine       $twigEngine
     * @param EntityManager    $entityManager
     * @param PaginationHelper $pagination
     * @param ExportHelper     $export
     */
    public function __construct(
        RequestStack $requestStack,
        TwigEngine $twigEngine,
        EntityManager $entityManager,
        PaginationHelper $pagination,
        ExportHelper $export
    ) {
        $this->request       = $requestStack->getMasterRequest();
        $this->twigEngine    = $twigEngine;
        $this->entityManager = $entityManager;
        $this->pagination    = $pagination;
        $this->export        = $export;
    }

    /**
     * @param string $tableType
     * @param array  $parameters
     *
     * @return $this
     *
     * @throws \Exception
     */
    public function init(string $tableType, $parameters = [])
    {
        $this->tableType = new $tableType();

        if (false === $this->tableType) {
            throw new \Exception('Invalid class of TableType');
        }

        $optionsResolver = new OptionsResolver();

        $this->tableType->configureOptions($optionsResolver);
        $this->parameters = $optionsResolver->resolve($parameters);

        return $this;
    }

    /**
     * @return Table
     */
    public function createView()
    {
        $actionPanelBuilder = new ActionPanelBuilder();
        $this->tableType->createActionPanel($actionPanelBuilder);

        return new Table(
            $this->getTableName(),
            get_class($this->tableType),
            $this->parameters,
            $this->getData(),
            $this->pagination->getPagination(),
            $this->getFilters(),
            $this->getColumns(),
            $this->getButtons(),
            $actionPanelBuilder->getActionPanel(),
            $this->getParameter(self::PARAMETER_TABLE_IS_ORDERED),
            $this->getParameter(self::PARAMETER_TABLE_HAS_CHECKBOXES),
            $this->export->getExport()
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createExport()
    {
        return $this->export
            ->setTable(
                new Table(
                    $this->getTableName(),
                    get_class($this->tableType),
                    $this->parameters,
                    $this->getData(),
                    $this->pagination->getExportPagination(),
                    $this->getFilters(),
                    $this->getColumns(),
                    [], [], false, false,
                    $this->export->getExport()
                )
            )
            ->getExportData();
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    private function getParameter($name)
    {
        return $this->parameters[$name];
    }

    /**
     * @return string
     */
    private function getTableName()
    {
        if (null !== $this->tableName) {
            return $this->tableName;
        }

        $defaultName     = $this->getParameter(self::PARAMETER_TABLE_NAME);
        $this->tableName = $defaultName;
        $content         = $this->request->getContent();

        if (true === empty($content)) {
            return $defaultName;
        }

        $params = json_decode($content, true);

        if (false === isset($params[self::PARAMETER_TABLE_NAME])) {
            return $defaultName;
        }

        if (null === trim($params[self::PARAMETER_TABLE_NAME])) {
            return $defaultName;
        }

        $this->tableName = trim($params[self::PARAMETER_TABLE_NAME]);

        return $this->tableName;
    }

    /**
     * @return Filter[]
     */
    private function getFilters()
    {
        $filterBuilder = new FilterBuilder();
        $this->tableType->createFilters($filterBuilder);
        $filters = $filterBuilder->getFilters();
        parse_str($this->request->get('filters'), $requestFilters);

        if (true === empty($requestFilters)) {
            return $filters;
        }

        foreach ($filters as $key => $filter) {
            if (false === isset($requestFilters[$filter->getName()])) {
                continue;
            }

            $filters[$key] = new Filter(
                $filter->getName(),
                $filter->getLabel(),
                $requestFilters[$filter->getName()],
                $filter->getOperation(),
                $filter->isAutoComplete()
            );
        }

        return $filters;
    }

    /**
     * @return Button[]
     */
    private function getButtons()
    {
        $buttonsBuilder = new ButtonBuilder();
        $this->tableType->createButtons($buttonsBuilder);

        $data    = $this->getData();
        $buttons = $buttonsBuilder->getButtons();
        $filteredButtons = [];

        if (true === empty($buttons)) {
            return [];
        }

        foreach($data as $row) {
            $rowButtons = [];

            foreach($buttons as $button) {
                $parameters = [];

                foreach($button->getParameters() as $name => $parameter) {
                    if (false === isset($row[$parameter])) {
                        $parameters[$name] = $parameter;

                        continue;
                    }

                    $parameters[$name] = $row[$parameter];
                }

                $rowButtons[] = new Button(
                    $button->getName(),
                    $button->getLabel(),
                    $button->getRoute(),
                    $parameters,
                    $button->getAttr(),
                    $button->getIcon(),
                    $button->getModalMessage()
                );
            }

            $filteredButtons[] = $rowButtons;
        }

        return $filteredButtons;
    }

    /**
     * @return Model\Column[]
     * @throws \Exception
     */
    private function getColumns()
    {
        $columnBuilder = new ColumnBuilder();
        $this->tableType->createColumns($columnBuilder);
        $columns = $columnBuilder->getColumns();

        if (true === empty($columns)) {
            throw new \Exception(
                sprintf(
                    'Columns for table %s not defined',
                    $this->getTableName()
                )
            );
        }

        return $columns;
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function getData()
    {
        if (false === empty($this->data)) {
            return $this->data;
        }

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $dataHelper   = new DataHelper(
            $this->request,
            $this->getTableName(),
            $this->getColumns(),
            $this->getFilters()
        );
        $this->tableType->createQuery($queryBuilder);

        if (false === $dataHelper->searchData($queryBuilder)) {
            $dataHelper->filterData($queryBuilder);
        }

        $this->pagination->setQuery(
            $queryBuilder->getQuery()
        );

        $pagination = $this->pagination->getPagination();
        $perPage    = $pagination->getPerPage();
        $data       = $this->pagination
            ->getQuery()
            ->setMaxResults($perPage)
            ->setFirstResult(($perPage * $pagination->getActualPage()) - $perPage)
            ->getArrayResult();

        $this->data = $dataHelper->getRows($data, $queryBuilder);

        return $this->data;
    }
}
