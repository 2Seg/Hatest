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
        if (key_exists('setter', $options)) {
            $setter = $options['setter'];
        } else {
            $setter = 'set' . $this->toCamelCaseSetter($property);
        }
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
