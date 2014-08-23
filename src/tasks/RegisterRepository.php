<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 15/05/14
 * Time: 4:26 PM
 */
namespace Stark\tasks;
use Stark\core\tasks\Task;

class RegisterRepository extends Task {

    /**
     * @var string
     */
    private $repositoryName;
    /**
     * @var mixed
     */
    private $className;

    public function setName($name) {
        if ($name == "") {
            throw new \InvalidArgumentException('Repository name cannot be null');
        }
        $this->repositoryName = $name;
    }
    public function setClassname($className) {
        $this->className = $className;
    }

    public function execute() {
        if (null === $this->name) {
            throw new \InvalidArgumentException('Expecting repository name');
        }
        if (null === $this->className) {
            throw new \InvalidArgumentException('Expecting className for repository ' . $this->repositoryName);
        }
        if (!$this->container->getTasksFactory()->registerTask($this->name, $this->className, $errorMsg)) {
            $this->pushError('Cannot register task ' . $this->name . ': ' . $errorMsg);
        }
    }

    public function getName() {
        return 'Repository registration';
    }

}