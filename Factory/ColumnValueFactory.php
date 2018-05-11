<?php

namespace KamilDuszynski\TableGeneratorBundle\Factory;

use KamilDuszynski\TableGeneratorBundle\Model\Column;

class ColumnValueFactory
{
    /**
     * @param mixed|null $mixedContent
     * @param Column     $column
     *
     * @return mixed
     * @throws \Exception
     */
    public static function createValueForColumn($mixedContent = null, Column $column)
    {
        if (null === $mixedContent) {
            return null;
        }

        $value           = $mixedContent;
        $property        = $column->getProperty();
        $decorator       = $column->getValueDecorator();
        $isArray         = is_array($mixedContent);
        $isDateTime      = $mixedContent instanceof \DateTime;
        $isObject        = is_object($mixedContent) && !$isDateTime;
        $isObjectOrArray = $isArray || $isObject;

        if (
            null === $property &&
            true === $isObjectOrArray &&
            null === $decorator
        ) {
            throw new \Exception(
                sprintf(
                    'Property for column %s not defined',
                    $column->getName()
                )
            );
        }

        if (false !== strpos($property, '.')) {
            $propertyExploded = explode('.', $property);
            $property         = $propertyExploded[1]; // pod indexem 0 jest alias tabeli
        }

        if (true === $isArray) {
            $value = $mixedContent[$property];
        }

        if (true === $isObject) {
            $getterName = sprintf(
                'get%s',
                ucfirst($property)
            );

            $value = $mixedContent->{$getterName}();
        }

        if (null !== $decorator) {
            return $decorator($value);
        }

        return $value;
    }
}
