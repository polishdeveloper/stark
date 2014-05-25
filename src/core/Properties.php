<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 04/05/14
 * Time: 4:41 PM
 */
namespace Stark\core;

class Properties {
    private $evaluationStack = array();

    private $properties = array();

    public function set($property, $value) {
        if ($property == '') {
            throw new \InvalidArgumentException('Cannot set property with empty name');
        }
        $this->properties[$property] = $value;
    }

    public function get($property) {
        return $this->evaluate($property);
    }

    private function evaluate($property) {
        if (array_key_exists($property, $this->evaluationStack)) {
            throw new \InvalidArgumentException("Cannot evaluate property $property, found cycle");
        }
        if (array_key_exists($property, $this->properties)) {
            $this->evaluationStack[$property] = true;;
            $value = $this->properties[$property];
            preg_match_all('/\\${([a-zA-Z0-9]+)}/', $value, $matches);
            if (count($matches) > 0) {
                foreach ($matches[1] as $innerProperty) {
                    $value = str_replace('${' . $innerProperty . '}', $this->evaluate($innerProperty), $value);
                }
            }
            unset($this->evaluationStack[$property]);
            return $value;
        } else {
            throw new \InvalidArgumentException("Property $property doesn't exist");
        }
    }
}