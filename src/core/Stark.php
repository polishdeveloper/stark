<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 04/05/14
 * Time: 2:29 PM
 */
namespace Stark\core;

use Stark\core\io\HooksXMLReader;
use Stark\core\tasks\Task;

final class Stark {
    const SUCCESS = 0;
    const FAILURE = 255;

    use ContainerAwareTrait;
    /**
     * @var HooksXMLReader;
     */
    private $xml;
    private $errorsCollection = array();
    /**
     * @var string
     */
    private $action;


    public function setArguments($arguments)
    {
        $this->validateArguments($arguments);
        $repoType = array_shift($arguments);
        $this->action   = $arguments[0];

        $this->container['repo'] = function(Container $container) use ($repoType, $arguments) {
            return $container->getRepoFactory()->getRepository($repoType, $arguments);
        };

        $this->loadHooks('hooks.xml');
    }


    protected function validateArguments(array $arguments)
    {
        if (!array_key_exists(0, $arguments)) {
            throw new \InvalidArgumentException('Missing repository type argument');
        }
        if (!array_key_exists(1, $arguments)) {
            throw new \InvalidArgumentException('Missing hook argument');
        }
    }

    public function execute()
    {
        $tasksDefinitions = $this->xml->getHooks($this->action);
        $tasks = $this->createTasks($tasksDefinitions);
        $this->executeTasks($tasks);
        $this->handleResponse();
        return empty($this->errorsCollection) ? self::SUCCESS : self::FAILURE;
    }


    private function executeTasks(array $tasks)
    {
        foreach ($tasks as $task) {
            try {
                $this->executeTask($task);
            } catch (\Exception $e)  {
                //task failed skip it. There is no need to break commit when things internally dont'w work
            }
        }
    }

    private function createTasks(array $tasksDefinitions)
    {
        $tasks = array();
        foreach ($tasksDefinitions as $taskDefinition) {
            $task = $this->createTask($taskDefinition);
            $tasks[] = $task;
        }
        return $tasks;
    }

    /**
     * @param string $filename
     */
    public function loadHooks($filename)
    {
        $this->xml = $this->container['configReader']->read($filename);
    }

    protected function createTask(array $taskDefinition)
    {
        return $this->container->getTasksFactory()->buildTask($taskDefinition['name'], $taskDefinition['params']);
    }

    private function executeTask(Task $task)
    {
        $task->execute();
        if (!$task->isSuccessful()) {
            $this->pushErrors($task);
        }
    }

    protected function pushErrors(Task $task) {
        if (!array_key_exists($task->getName(), $this->errorsCollection)) {
            $this->errorsCollection[$task->getName()] = array();
        }
        $this->errorsCollection[$task->getName()] = array_merge($this->errorsCollection[$task->getName()], $task->getErrors());
    }

    protected function handleResponse()
    {
       $renderer = $this->container->getRenderer();
       $renderer->setErrors($this->errorsCollection);
       $renderer->render();
    }

}