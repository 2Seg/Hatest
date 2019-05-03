<?php

namespace Hatest\Traits;

trait SetterTrait
{
    /**
     * @dataProvider providerGetterAndSetter
     *
     * @param $property
     * @param $value
     * @param array $options
     */
    public function testSetter($property, $value, array $options = [])
    {
        $setter = $this->getFunctionName('set', $this->toCamelCaseSetter($property), $options);
        $object = $this->init();
        $object->$setter($value);
        
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty($property);
        $property->setAccessible(true);
        $newValue = $property->getValue($object);

        $this->assertSame($value, $newValue);
    }

    /**
     * Change property to camel case for methods
     *
     * @param $property
     *
     * @return mixed
     */
    private function toCamelCaseSetter($property)
    {
        return ucfirst(
            preg_replace_callback(
                '/[_-](.)/',
                function ($match) {
                    return strtoupper($match[1]);
                },
                $property
            )
        );
    }

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
