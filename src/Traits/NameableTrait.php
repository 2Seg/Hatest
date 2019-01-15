<?php

namespace Hatest\Traits;

trait NameableTrait
{
    /**
     * @param string $operation
     * @param string $propertyName
     * @param array $options
     *
     * @return string
     */
    public function getFunctionName($operation, $propertyName, array $options = [])
    {
        switch ($operation) {
            case 'get':
                return key_exists('getter', $options) ? $options['getter'] : 'get'.$propertyName;
            case 'add':
                return key_exists('adder', $options) ? $options['adder'] : 'add'.$propertyName;
            case 'remove':
                return key_exists('remover', $options) ? $options['remover'] : 'remove'.$propertyName;
            case 'has':
                return key_exists('haser', $options) ? $options['haser'] : 'has'.$propertyName;
            case 'set':
                return key_exists('setter', $options) ? $options['setter'] : 'set'.$propertyName;
        }

        return '';
    }
}
