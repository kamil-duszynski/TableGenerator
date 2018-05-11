<?php

namespace KamilDuszynski\TableGeneratorBundle\Builder;

use KamilDuszynski\TableGeneratorBundle\Model\Column;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ColumnBuilder
{
    /**
     * @var Column[]
     */
    private $columns;

    /**
     * @param string $name
     * @param string $label
     * @param array  $data
     * @param $valueDecorator
     *
     * @return $this
     */
    public function add($name, $label, $data = [], $valueDecorator = null)
    {
        $optionResolver = new OptionsResolver();
        $optionResolver->setDefaults(
            [
                'property'   => null,
                'route'      => null,
                'parameters' => [],
                'attr'       => [],
            ]
        );
        $optionResolver->addAllowedTypes(
            'property',
            [
                'string',
                'null',
            ]
        );
        $optionResolver->addAllowedTypes(
            'route',
            [
                'string',
                'null',
            ]
        );
        $optionResolver->addAllowedTypes('attr', 'array');
        $data = $optionResolver->resolve($data);

        $this->columns[$name] = new Column(
            $name,
            $label,
            $data['property'],
            $data['route'],
            $data['parameters'],
            $data['attr'],
            $valueDecorator
        );

        return $this;
    }

    /**
     * @return Column[]
     */
    public function getColumns()
    {
        return $this->columns;
    }
}