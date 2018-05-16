<?php

namespace KamilDuszynski\TableGeneratorBundle\Builder;

use KamilDuszynski\TableGeneratorBundle\Model\Filter;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterBuilder
{
    /**
     * @var Filter[]
     */
    private $filters;

    /**
     * @return Filter[]
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param string $name
     * @param string $label
     * @param array  $data
     *
     * @return $this
     */
    public function add($name, $label, $data = [])
    {
        $optionResolver = new OptionsResolver();
        $optionResolver->setDefaults(
            [
                'type'           => Filter::TYPE_INPUT,
                'property'       => null,
                'value'          => null,
                'operation'      => '=',
                'isAutoComplete' => false,
            ]
        );
        $optionResolver->addAllowedTypes('isAutoComplete', 'boolean');
        $optionResolver->addAllowedTypes('type', 'string');
        $optionResolver->addAllowedTypes(
            'property',
            [
                'string',
                'null',
            ]
        );
        $optionResolver->addAllowedTypes(
            'value',
            [
                'string',
                'null',
            ]
        );
        $optionResolver->addAllowedTypes('operation', 'string');

        $data = $optionResolver->resolve($data);

        $this->filters[] = new Filter(
            $name,
            $label,
            $data['type'],
            $data['property'],
            $data['value'],
            $data['operation'],
            $data['isAutoComplete']
        );

        return $this;
    }
}