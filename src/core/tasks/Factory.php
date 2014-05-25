<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 15/05/14
 * Time: 12:33 AM
 */
namespace Stark\core\tasks;

use Stark\core\Container;

class Factory {
    private $taskToClassNameMap = array(
        /** System tasks */
        'property'           => 'Stark\tasks\Property',
        'registerTask'       => 'Stark\tasks\RegisterTask',
        'registerRepository' => 'Stark\tasks\RegisterRepository',

        /** Common tasks  */
        'comment'            => 'Stark\tasks\Comment',
        'fileFilter'         => 'Stark\tasks\FileFilter',
        'mail'               => 'Stark\tasks\Mail',
        'externalCommand'    => 'Stark\tasks\ExternalCommand',
        'log'                => 'Stark\tasks\Log',

        /** PHP Tasks */
        'phpLint'         => 'Stark\tasks\PHPLint'

        /** Bugtracking tasks */


    );
    private $container;


    public function setContainer(Container $container) {
        $this->container = $container;
    }

    public function registerTask($name, $className, &$errorMessage) {
        if (array_key_exists($name, $this->taskToClassNameMap)) {
            $errorMessage = "Task $name already exists";
            return false;
        }
        if (!class_exists($className)) {
            $errorMessage = "Class $className doesn't exist";
        }
        $this->taskToClassNameMap[$name] = $className;
    }

    public function buildTask($name, $params) {
        $task = $this->createTaskInstance($name);
        $task->setContainer($this->container);
        foreach($params as $param => $value) {
            $method = 'set' . ucfirst($param);
            if (!method_exists($task, $method)) {
                throw new \InvalidArgumentException("Task $name doesn't support $param parameter");
            }
            $task->$method($value);
        }
        return $task;
    }


    protected function createTaskInstance($name) {
        if (!array_key_exists($name, $this->taskToClassNameMap)) {
            throw new \InvalidArgumentException("Task $name doesn't exist");
        }
        return new $this->taskToClassNameMap[$name]();
    }


}