<?php

namespace Hatest\Doctrine\Traits;

use Doctrine\Common\Collections\Collection;

trait CollectionTrait
{
    /**
     * Change property to camel case for methods
     *
     * @param $property
     *
     * @return mixed
     */
    private function toCamelCaseCollection($property)
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
     * @dataProvider providerCollection
     *
     * @param $property
     */
    public function testGetElements($property)
    {
        $getter = 'get' . $this->toCamelCaseCollection($property) . 's';
        $object = $this->init();

        $this->assertTrue($object->$getter() instanceof Collection);
    }

    /**
     * @dataProvider providerCollection
     *
     * @param $property
     * @param $element
     */
    public function testAddElement($property, $element)
    {
        $propertyName = $this->toCamelCaseCollection($property);
        $has = 'has' . $propertyName;
        $setter = 'add' . $propertyName;

        $object = $this->init();
        $object->$setter($element);

        $this->assertTrue($object->$has($element));
    }

    /**
     * @dataProvider providerCollection
     *
     * @param $property
     * @param $element
     */
    public function testHasElement($property, $element)
    {
        $propertyName = $this->toCamelCaseCollection($property);
        $has = 'has' . $propertyName;
        $setter = 'add' . $propertyName;

        $object = $this->init();

        $this->assertInstanceOf(get_class($object), $object->$setter($element));
        $this->assertTrue($object->$has($element));
    }

    /**
     * @dataProvider providerCollection
     *
     * @param $property
     * @param $element
     */
    public function testRemoveElement($property, $element)
    {
        $propertyName = $this->toCamelCaseCollection($property);
        $remove = 'remove' . $propertyName;
        $has = 'has' . $propertyName;
        $setter = 'add' . $propertyName;

        $object = $this->init();

        $this->assertInstanceOf(get_class($object), $object->$setter($element));
        $this->assertInstanceOf(get_class($object), $object->$remove($element));
        $this->assertFalse($object->$has($element));
    }
}
