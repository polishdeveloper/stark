<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 15/05/14
 * Time: 4:26 PM
 */
namespace Stark\tasks;
use Stark\core\tasks\Task;

class Property extends Task {

    /**
     * @var string
     */
    private $propertyName;
    /**
     * @var mixed
     */
    private $value;

    public function setName($propertyName) {
        if ($propertyName == "") {
            throw new \InvalidArgumentException('Property name cannot be null');
        }
        $this->propertyName = $propertyName;
    }
    public function setValue($value) {
        $this->value = $value;
    }

    public function execute() {
        if (null === $this->propertyName) {
            throw new \InvalidArgumentException('Expecting property name');
        }
        echo "Setting up property {$this->propertyName} = {$this->value}\n";
        $this->container->getProperties()->set($this->propertyName, $this->value);
    }


    public function getName() {
        return 'Property Setter';
    }
}