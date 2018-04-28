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
                'value'          => '',
                'operation'      => '=',
                'isAutoComplete' => false,
            ]
        );
        $optionResolver->addAllowedTypes('isAutoComplete', 'boolean');
        $optionResolver->addAllowedTypes('value', 'string');
        $optionResolver->addAllowedTypes('operation', 'string');

        $data = $optionResolver->resolve($data);

        $this->filters[] = new Filter($name, $label, $data['value'], $data['operation'], $data['isAutoComplete']);

        return $this;
    }
}