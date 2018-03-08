<?php

namespace Hatest\Traits;

trait GetterTrait
{
    /**
     * Update the property to an object
     *
     * @param mixed  $object
     * @param string $property
     * @param string $value
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
     */
    public function testGetter($property, $value)
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
            $getter = 'get' . $this->toCamelCaseGetter($property);
        }
        $this->assertSame($value, $object->$getter());
    }

    /**
     * test getId
     */
    public function testGetId()
    {
        $object = $this->init();
        $this->prepareProperty($object, 'id', 42);

        $this->assertSame(42, $object->getId());
    }
}
