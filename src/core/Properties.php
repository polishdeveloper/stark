<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 04/05/14
 * Time: 4:41 PM
 */
namespace Stark\core;

class Properties {
    use ContainerAwareTrait;

    /**
     * @var array
     */
    private $evaluationStack = array();

    /**
     * @var array
     */
    private $properties = array();
    /**
     * @var array
     */
    private $lazyProperties = array();


    public function initializeDefault() {
        $container = $this->getContainer();;

        $this->set('time', date('H:i:s', $container->getTimestamp()));
        $this->set('date', date('Y-m-d', $container->getTimestamp()));
        $this->set('timestamp',  $container->getTimestamp());


        $this->set('author', function() use ($container) {
            return $container->getRepo()->getAuthor();
        });
        $this->set('message', function() use ($container) {
            return $container->getRepo()->getComment();
        });
    }

    /**
     * @param string $property
     */
    public function set($property, $value) {
        if ($property == '') {
            throw new \InvalidArgumentException('Cannot set property with empty name');
        }
        $this->properties[$property] = $value;
    }

    public function get($property) {
        return $this->evaluate($property);
    }

    public function expand($message) {
        preg_match_all('/\\${([a-zA-Z0-9]+)}/', $message, $matches);
        if (count($matches) > 0) {
            foreach ($matches[1] as $innerProperty) {
                $message = str_replace('${' . $innerProperty . '}', $this->evaluate($innerProperty), $message);
            }
        }
        return $message;
    }

    private function evaluate($property) {
        if (array_key_exists($property, $this->evaluationStack)) {
            throw new \InvalidArgumentException("Cannot evaluate property $property, found cycle");
        }
        if (array_key_exists($property, $this->properties)) {
            $this->evaluationStack[$property] = true;
            $rawValue = $this->getRawValue($property);
            $value = $this->expand($rawValue);
            unset($this->evaluationStack[$property]);
            return $value;
        } else {
            throw new \InvalidArgumentException("Property $property doesn't exist");
        }
    }

    private function getRawValue($propertyName) {
        if (is_callable($this->properties[$propertyName])) {
            if (!array_key_exists($propertyName, $this->lazyProperties)) {
                $this->lazyProperties[$propertyName] = $this->properties[$propertyName]();
            }
            return $this->lazyProperties[$propertyName];
        } else {
            return $this->properties[$propertyName];
        }
    }
}