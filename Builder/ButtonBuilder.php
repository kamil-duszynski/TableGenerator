<?php

namespace KamilDuszynski\TableGeneratorBundle\Builder;

use KamilDuszynski\TableGeneratorBundle\Model\Button;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ButtonBuilder
{
    /**
     * @var Button[]
     */
    private $buttons;

    /**
     * @param string      $name
     * @param string|null $label
     * @param array       $data
     * @param string|null $icon
     * @param string|null $modalMessage
     *
     * @return $this
     */
    public function add($name, $label = null, $data = [], $icon = null, $modalMessage = null)
    {
        $optionResolver = new OptionsResolver();
        $optionResolver->setDefaults(
            [
                'route'      => '',
                'parameters' => [],
                'attr'       => [],
            ]
        );
        $optionResolver->addAllowedTypes('route', 'string');
        $optionResolver->addAllowedTypes('parameters', 'array');
        $optionResolver->addAllowedTypes('attr', 'array');
        $data = $optionResolver->resolve($data);

        $this->buttons[] = new Button($name, $label, $data['route'], $data['parameters'], $data['attr'], $icon, $modalMessage);

        return $this;
    }

    /**
     * @return Button[]
     */
    public function getButtons()
    {
        return $this->buttons;
    }
}