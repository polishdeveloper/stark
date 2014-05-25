<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 04/05/14
 * Time: 2:29 PM
 */
namespace Stark\core;



use Stark\core\io\HooksXMLReader;
use Stark\core\repository\Factory;
use Stark\core\tasks\Task;

final class Stark {

    /**
     * @var HooksXMLReader;
     */
    private $xml;
    private $container;
    private $errorsCollection = array();


    protected function initializeContainer() {
        $this->container = new Container();
        $this->container['tasksFactory'] = function($container)  {
            $factory = new \Stark\core\tasks\Factory();
            $factory->setContainer($container);
            return $factory;
        };
    }

    public function __construct($arguments) {



        array_shift($arguments); //drop filename
        $this->validateArguments($arguments);
        $repoType = array_shift($arguments);
        $action   = array_shift($arguments);

        $this->initializeContainer();
        $this->container['repo'] = function() use ($repoType, $arguments) {
            $factory = new Factory();
            return $factory->getRepository($repoType, $arguments);
        };

        $this->action = $action;
        $this->loadHooks('hooks.xml');
    }


    protected function validateArguments(array $arguments) {
        if (!array_key_exists(0, $arguments)) {
            throw new \InvalidArgumentException('Missing repository type argument');
        }
        if (!array_key_exists(1, $arguments)) {
            throw new \InvalidArgumentException('Missing hook argument');
        }
    }

    public function execute() {
        $tasks = $this->xml->getHooks($this->action);
        foreach ($tasks as $taskDefinition) {
            $task = $this->createTask($taskDefinition);
            $this->executeTask($task);
        }
        $this->handleResponse();
    }

    public function loadHooks($filename) {
        $this->xml = new HooksXMLReader($filename);
    }

    protected function createTask(array $taskDefinition) {
        return $this->container->getTasksFactory()->buildTask($taskDefinition['name'], $taskDefinition['params']);
    }

    private function executeTask(Task $task) {
        try {
            $task->execute();
            if (!$task->isSuccessful()) {
                $this->pushErrors($task);
            }
        } catch (Exception $e) {
            //what to do, exception
        }
    }

    protected function pushErrors(Task $task) {
        if (!array_key_exists($task->getName(), $this->errorsCollection)) {
            $this->errorsCollection[$task->getName()] = array();
        }
        $this->errorsCollection[$task->getName()] = array_merge($this->errorsCollection[$task->getName()], $task->getErrors());
    }

    protected function handleResponse() {
       if (!empty($this->errorsCollection)) {
           echo "Cannot perform action. Errors: \n";
           $i = 0;
           foreach ($this->errorsCollection as $taskName => $errors) {
               ++$i;
               echo "$i. Hook $taskName failed with errors : \n";
               foreach ($errors as $error) {
                   echo "   $error \n";
               }
           }


       }
    }

}