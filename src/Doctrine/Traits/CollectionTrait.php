<?php

namespace Hatest\Doctrine\Traits;

use Doctrine\Common\Collections\Collection;
use Hatest\Traits\NameableTrait;

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
     * @param $element
     * @param array $options
     */
    public function testGetElements($property, $element, array $options = [])
    {
        $getter = $this->getFunctionName('get', $this->toPlural($this->toCamelCaseCollection($property)), $options);
        $object = $this->init();

        $this->assertTrue($object->$getter() instanceof Collection);
    }

    /**
     * @param $word
     *
     * @return string
     */
    private function toPlural($word)
    {
        $character = $word[strlen($word) - 1];
        $previous = $word[strlen($word) - 2];

        if ($character === 'y' && !in_array($previous, ['a', 'e', 'i', 'o', 'u', 'y'])) {
            return substr($word, 0, -1) . 'ies';
        }

        if ($character === 'f') {
            return substr($word, 0, -1) . 'ves';
        }

        if (($character === 'e' && $previous === 'f')) {
            return substr($word, 0, -2) . 'ves';
        }

        if ($character === 'x' ||$character === 's' || $character === 'o' || ($character === 'h' && in_array($previous, ['c', 's']))) {
            return $word . 'es';
        }

        return $word . 's';
    }

    /**
     * @dataProvider providerCollection
     *
     * @param $property
     * @param $element
     * @param array $options
     */
    public function testAddElement($property, $element, array $options = [])
    {
        $propertyName = $this->toCamelCaseCollection($property);
        $has = $this->getFunctionName('has', $propertyName, $options);
        $setter = $this->getFunctionName('add', $propertyName, $options);

        $object = $this->init();
        $object->$setter($element);

        $this->assertTrue($object->$has($element));
    }

    /**
     * @dataProvider providerCollection
     *
     * @param $property
     * @param $element
     * @param array $options
     */
    public function testHasElement($property, $element, array $options = [])
    {
        $propertyName = $this->toCamelCaseCollection($property);
        $has = $this->getFunctionName('has', $propertyName, $options);
        $setter = $this->getFunctionName('add', $propertyName, $options);

        $object = $this->init();

        $this->assertInstanceOf(get_class($object), $object->$setter($element));
        $this->assertTrue($object->$has($element));
    }

    /**
     * @dataProvider providerCollection
     *
     * @param $property
     * @param $element
     * @param array $options
     */
    public function testRemoveElement($property, $element, array $options = [])
    {
        $propertyName = $this->toCamelCaseCollection($property);

        $remove = $this->getFunctionName('remove', $propertyName, $options);
        $has = $this->getFunctionName('has', $propertyName, $options);
        $setter = $this->getFunctionName('add', $propertyName, $options);

        $object = $this->init();

        $this->assertInstanceOf(get_class($object), $object->$setter($element));
        $this->assertInstanceOf(get_class($object), $object->$remove($element));
        $this->assertFalse($object->$has($element));
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
