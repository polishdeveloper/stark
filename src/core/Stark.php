<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 04/05/14
 * Time: 2:29 PM
 */
namespace Stark\core;

use Stark\core\io\HooksXMLReader;
use Stark\core\io\Output;
use Stark\core\repository\Factory;
use Stark\core\tasks\Task;

final class Stark {

    /**
     * @var HooksXMLReader;
     */
    private $xml;
    /**
     * @var Container
     */
    private $container;
    private $errorsCollection = array();
    private $action;

    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    public function setArguments($arguments)
    {
        $this->validateArguments($arguments);
        $repoType = array_shift($arguments);
        $this->action   = $arguments[0];

        $this->container['repo'] = function() use ($repoType, $arguments) {
            $factory = new Factory();
            return $factory->getRepository($repoType, $arguments);
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
    }


    private function executeTasks(array $tasks)
    {
        foreach ($tasks as $task) {
            try {
                $this->executeTask($task);
            } catch (\Exception $e)  {

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

    /**
     * Todo - create an output class for that
     */
    protected function handleResponse()
    {
       $renderer = $this->container->getRenderer();
       $renderer->setErrors($this->errorsCollection);
       $renderer->render();
    }

}