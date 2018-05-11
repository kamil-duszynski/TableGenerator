<?php

namespace KamilDuszynski\TableGeneratorBundle\Twig;

use KamilDuszynski\TableGeneratorBundle\Helper\ExportHelper;
use KamilDuszynski\TableGeneratorBundle\Model\Button;
use KamilDuszynski\TableGeneratorBundle\Model\Column;
use KamilDuszynski\TableGeneratorBundle\Model\Export;
use KamilDuszynski\TableGeneratorBundle\Model\Pagination;
use KamilDuszynski\TableGeneratorBundle\Model\Table;
use Symfony\Component\Routing\Router;

class TableGeneratorExtension extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    private $twigEnvironment;

    /**
     * @var string
     */
    private $template;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var ExportHelper
     */
    private $exportHelper;

    /**
     * @param string            $template
     * @param \Twig_Environment $twigEnvironment
     * @param Router            $router
     * @param ExportHelper      $exportHelper
     */
    public function __construct(
        $template,
        \Twig_Environment $twigEnvironment,
        Router $router,
        ExportHelper $exportHelper
    )
    {
        $this->template        = $template;
        $this->twigEnvironment = $twigEnvironment;
        $this->router          = $router;
        $this->exportHelper    = $exportHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'generateTable',
                [$this, 'generateTable'],
                [
                    'is_safe' => ['html']
                ]
            ),
            new \Twig_SimpleFunction('tableRoutePath', [$this, 'tableRoutePath']),
            new \Twig_SimpleFunction(
                'generateTableButton',
                [$this, 'generateTableButton'],
                [
                    'is_safe' => ['html'],
                ]
            ),
            new \Twig_SimpleFunction(
                'generateTableActionPanel',
                [$this, 'generateTableActionPanel'],
                [
                    'is_safe' => ['html'],
                ]
            ),
            new \Twig_SimpleFunction(
                'generateTableExportPanel',
                [$this, 'generateTableExportPanel'],
                [
                    'is_safe' => ['html'],
                ]
            ),
            new \Twig_SimpleFunction(
                'generateTableButtonAttr',
                [$this, 'generateTableButtonAttr'],
                [
                    'is_safe' => ['html'],
                ]
            ),
            new \Twig_SimpleFunction(
                'generateTablePagination',
                [$this, 'generateTablePagination'],
                [
                    'is_safe' => ['html'],
                ]
            ),
            new \Twig_SimpleFunction(
                'generateTableFilters',
                [$this, 'generateTableFilters'],
                [
                    'is_safe' => ['html'],
                ]
            ),
        ];
    }

    /**
     * @param Table       $table
     * @param string|null $template
     *
     * @return string
     */
    public function generateTable(Table $table, $template = null)
    {
        if (null === $template) {
            $template = $this->template;
        }

        return $this->twigEnvironment->render(
            $template,
            [
                'name'          => $table->getName(),
                'class'         => $table->getClass(),
                'header'        => $table->getColumns(),
                'filters'       => $table->getFilters(),
                'data'          => $table->getData(),
                'buttons'       => $table->getButtons(),
                'isOrdered'     => $table->isOrdered(),
                'hasCheckboxes' => $table->hasCheckboxes(),
                'actionPanel'   => $table->getActionPanel(),
                'exportPanel'   => $table->getExport(),
                'pagination'    => $table->getPagination(),
                'parameters'    => $table->getParameters(),
            ]
        );
    }

    /**
     * @param Button $button
     *
     * @return mixed|string
     */
    public function generateTableButton(Button $button)
    {
        return $this->twigEnvironment->render(
            '@TableGenerator/Table/UIElements/button.html.twig',
            [
                'name'         => $button->getName(),
                'label'        => $button->getLabel(),
                'modalMessage' => $button->getModalMessage(),
                'route'        => $button->getRoute(),
                'parameters'   => $button->getParameters(),
                'icon'         => $button->getIcon(),
                'attr'         => $button->getAttr(),
            ]
        );
    }

    /**
     * @param Export $exportPanel
     *
     * @return mixed|string
     */
    public function generateTableExportPanel(Export $exportPanel)
    {
        return $this->twigEnvironment->render(
            '@TableGenerator/Table/UIElements/export.panel.html.twig',
            [
                'formats' => $exportPanel->getFormats(),
                'actual'  => $exportPanel->getActualFormat(),
            ]
        );
    }

    /**
     * @param Pagination $pagination
     *
     * @return mixed|string
     */
    public function generateTablePagination(Pagination $pagination)
    {
        $pages = [];

        if (1 >= $pagination->getActualPage()) {
            $first = 1;
            $last  = 3;
        } else {
            $first = $pagination->getActualPage() - 1;
            $last  = $pagination->getActualPage() + 1;
        }

        if ($pagination->getPagesCount() <= $last) {
            $last  = $pagination->getPagesCount();
            $first = $last - 2;
        }

        if (1 >= $last) {
            $last = 1;
        }

        if (1 >= $first) {
            $first = 1;
        }

        for ($page = $first; $page <= $last; $page++) {
            $pages[] = $page;
        }

        return $this->twigEnvironment->render(
            '@TableGenerator/Table/UIElements/pagination.html.twig',
            [
                'firstPage'  => $pagination->getFirstPage(),
                'actualPage' => $pagination->getActualPage(),
                'nextPage'   => $pagination->getNextPage(),
                'lastPage'   => $pagination->getLastPage(),
                'prevPage'   => $pagination->getPrevPage(),
                'totalCount' => $pagination->getTotalCount(),
                'perPage'    => $pagination->getPerPage(),
                'pages'      => $pages,
                'items'      => $pagination->getItems(),
            ]
        );
    }

    /**
     * @param array $actionPanel
     *
     * @return mixed|string
     */
    public function generateTableActionPanel($actionPanel = [])
    {
        return $this->twigEnvironment->render(
            '@TableGenerator/Table/UIElements/action.panel.html.twig',
            [
                'actionPanel' => $actionPanel,
            ]
        );
    }

    /**
     * @param array $filters
     *
     * @return mixed|string
     */
    public function generateTableFilters($filters = [])
    {
        return $this->twigEnvironment->render(
            '@TableGenerator/Table/UIElements/filters.html.twig',
            [
                'filters' => $filters,
            ]
        );
    }

    /**
     * @param string $name
     * @param array  $attr
     *
     * @return mixed|string
     */
    public function generateTableButtonAttr($name, $attr = [])
    {
        if (false === empty($attr)) {
            foreach ($attr as $attrName => $value) {
                if ('class' === $attrName) {
                    $value = sprintf(
                        '%s button-%s',
                        $value,
                        $name
                    );
                }

                $attr[$attrName] = $value;
            }
        }

        return $this->twigEnvironment->render(
            '@TableGenerator/Table/UIElements/button.attr.html.twig',
            [
                'attr' => $attr,
                'name' => $name,
            ]
        );
    }


    /**
     * @param Column|null $hColumn
     * @param $row
     *
     * @return null|string
     * @throws \Exception
     */
    public function tableRoutePath($hColumn = null, $row)
    {
        if (null === $hColumn) {
            return null;
        }

        if (true === empty($hColumn->getRoute())) {
            return null;
        }

        $parameters = $hColumn->getParameters();

        if (true === empty($parameters)) {
            return $this->router->generate($hColumn->getRoute(), []);
        }

        foreach ($parameters as $name => $column) {
            if (false === isset($row[$column])) {
                throw new \Exception(
                    sprintf(
                        'TableRoutePath error: no %s parameter for route %s',
                        $name,
                        $hColumn['route']
                    )
                );
            }

            $parameters[$name] = $row[$column];
        }

        return $this->router->generate($hColumn->getRoute(), $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'table_generator.twig_extension';
    }
}
