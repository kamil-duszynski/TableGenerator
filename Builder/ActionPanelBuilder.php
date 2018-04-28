<?php

namespace KamilDuszynski\TableGeneratorBundle\Builder;

use KamilDuszynski\TableGeneratorBundle\Model\Button;

class ActionPanelBuilder extends ButtonBuilder
{
    /**
     * @return Button[]
     */
    public function getActionPanel()
    {
        return $this->getButtons();
    }
}