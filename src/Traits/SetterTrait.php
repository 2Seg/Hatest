<?php

namespace Hatest\Traits;

trait SetterTrait
{
    use NameableTrait;

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
        $this->assertAttributeSame($value, $property, $object);
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
}
