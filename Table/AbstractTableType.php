<?php

namespace KamilDuszynski\TableGeneratorBundle\Table;

use KamilDuszynski\TableGeneratorBundle\Builder\ActionPanelBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractTableType implements TableTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function createActionPanel(ActionPanelBuilder $builder) {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'isOrdered'     => false,
                'hasCheckboxes' => true,
                'name'          => sprintf('table_%d', time()),
            ]
        );
    }
}