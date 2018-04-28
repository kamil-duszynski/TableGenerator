<?php

namespace KamilDuszynski\TableGeneratorBundle\Table;

use Doctrine\ORM\QueryBuilder;
use KamilDuszynski\TableGeneratorBundle\Builder\ActionPanelBuilder;
use KamilDuszynski\TableGeneratorBundle\Builder\ButtonBuilder;
use KamilDuszynski\TableGeneratorBundle\Builder\ColumnBuilder;
use KamilDuszynski\TableGeneratorBundle\Builder\FilterBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface TableTypeInterface
{
    /**
     * @param FilterBuilder $builder
     *
     * @return void
     */
    public function createFilters(FilterBuilder $builder);

    /**
     * @param ButtonBuilder $builder
     *
     * @return void
     */
    public function createButtons(ButtonBuilder $builder);

    /**
     * @param ActionPanelBuilder $builder
     *
     * @return void
     */
    public function createActionPanel(ActionPanelBuilder $builder);

    /**
     * @param QueryBuilder $builder
     *
     * @return void
     */
    public function createQuery(QueryBuilder $builder);

    /**
     * @param ColumnBuilder $builder
     *
     * @return void
     */
    public function createColumns(ColumnBuilder $builder);

    /**
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver);
}