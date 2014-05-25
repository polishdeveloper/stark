<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 15/05/14
 * Time: 4:26 PM
 */
namespace Stark\core\tasks;

class RegisterTask extends Task {

    /**
     * @var string
     */
    private $name;
    /**
     * @var mixed
     */
    private $className;

    public function setName($name) {
        if ($name == "") {
            throw new \InvalidArgumentException('Task name cannot be null');
        }
        $this->name = $name;
    }
    public function setClassName($className) {
        $this->className = $className;
    }

    public function execute() {
        if (null === $this->name) {
            throw new \InvalidArgumentException('Expecting task name');
        }
        if (null === $this->className) {
            throw new \InvalidArgumentException('Expecting className for task ' . $this->task);
        }
        if (!$this->container->getTasksFactory()->registerTask($this->name, $this->className, $errorMsg)) {
            $this->pushError('Cannot register task ' . $this->name . ': ' . $errorMsg);
        }
    }

    public function getName() {
        return 'Task registration';
    }

}