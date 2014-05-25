<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 15/05/14
 * Time: 4:26 PM
 */
namespace Stark\core\tasks;

class Property extends Task {

    /**
     * @var string
     */
    private $name;
    /**
     * @var mixed
     */
    private $value;

    public function setName($name) {
        if ($name == "") {
            throw new \InvalidArgumentException('Property name cannot be null');
        }
        $this->name = $name;
    }
    public function setValue($value) {
        $this->value = $value;
    }

    public function execute() {
        if (null === $this->name) {
            throw new \InvalidArgumentException('Expecting property name');
        }
        $this->properties->set($this->name, $this->value);
    }

}