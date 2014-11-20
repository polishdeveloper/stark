<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 15/05/14
 * Time: 12:33 AM
 */
namespace Stark\core\tasks;

use Stark\core\Container;
use Stark\core\ContainerAwareTrait;

class Factory {
    use ContainerAwareTrait;

    private $taskToClassNameMap = array(
        /** System tasks */
        'property'            => 'Stark\tasks\Property',
        'register_task'       => 'Stark\tasks\RegisterTask',
        'register_repository' => 'Stark\tasks\RegisterRepository',

        /** Common tasks  */
        'comment'             => 'Stark\tasks\Comment',
        'file_filter'         => 'Stark\tasks\FileFilter',
        'mail'                => 'Stark\tasks\Mail',
        'external_command'    => 'Stark\tasks\ExternalCommand',
        'log'                 => 'Stark\tasks\Log',

        /** PHP Tasks */
        'phplint'             => 'Stark\tasks\PHPLint'

        /** Bugtracking tasks */


    );

    public function registerTask($name, $className, &$errorMessage) {
        if (array_key_exists($name, $this->taskToClassNameMap)) {
            $errorMessage = "Task $name already exists";
            return false;
        }
        if (!class_exists($className)) {
            $errorMessage = "Class $className doesn't exist";
            return false;
        }
        $this->taskToClassNameMap[$name] = $className;
        return true;
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


    /**
     * @param string $name
     * @return Task
     */
    protected function createTaskInstance($name) {
        $taskId = mb_strtolower($name);
        if (!array_key_exists($taskId, $this->taskToClassNameMap)) {
            throw new \InvalidArgumentException("Task $name doesn't exist");
        }
        return new $this->taskToClassNameMap[$taskId]();
    }


}