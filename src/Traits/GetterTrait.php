<?php

namespace Hatest\Traits;

trait GetterTrait
{
    /**
     * Update the property to an object
     *
     * @param mixed $object
     * @param string $property
     * @param string $value
     *
     * @throws \ReflectionException
     */
    public function prepareProperty($object, $property, $value)
    {
        $reflectionClass = new \ReflectionClass($object);
        $propertyUpdated = $reflectionClass->getProperty($property);
        $propertyUpdated->setAccessible(true);
        $propertyUpdated->setValue($object, $value);
    }

    /**
     * Change property to camel case for methods
     *
     * @param $property
     *
     * @return mixed
     */
    private function toCamelCaseGetter($property)
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
     * @dataProvider providerGetterAndSetter
     * test getters
     *
     * @param string $property
     * @param string $value
     * @param array $options
     *
     * @throws \ReflectionException
     */
    public function testGetter($property, $value, array $options = [])
    {
        $object = $this->init();
        $this->prepareProperty($object, $property, $value);
        if (is_bool($value)) {
            $getter = '';
            $property = $this->toCamelCaseGetter($property);

            if (!preg_match('#^Is[A-Z]#', $property)) {
                $getter .= 'is';
            }

            $getter .= $property;
        } else {
            $getter = $this->getFunctionName('get', $this->toCamelCaseGetter($property), $options);
        }
        $this->assertSame($value, $object->$getter());
    }

    /**
     * test getId
     *
     * @throws \ReflectionException
     */
    public function testGetId()
    {
        $object = $this->init();
        $this->prepareProperty($object, 'id', 42);

        $this->assertSame(42, $object->getId());
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
