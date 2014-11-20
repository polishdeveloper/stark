<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 15/05/14
 * Time: 3:20 PM
 */
namespace Stark\core;

use Stark\core\tasks\Factory as TaskFactory;
use Stark\core\repository\Factory as RepoFactory;

class Container extends \Pimple\Container {

    /**
     * @return TaskFactory
     */
    public function getTasksFactory() {
        return $this['tasksFactory'];
    }

    /**
     * @return RepoFactory
     */
    public function getRepoFactory()
    {
        return $this['repoFactory'];

    }
    /**
     * @return Properties
     */
    public function getProperties() {
        return $this['properties'];
    }

    public function setRepo(Repository $repo) {
        $this['repo'] = $repo;
    }

    /**
     * @return Repository
     */
    public function getRepo(){
        return $this['repo'];
    }

    /**
     * @param Properties $properties
     */
    public function setProperties(Properties $properties) {
        $this['properties'] = $properties;
    }

    public function getRenderer()
    {
        return $this['renderer'];
    }

    public function getOutput()
    {
        return $this['output'];
    }

    public function getTimestamp()
    {
        if (!isset($this['timestamp'])) {
            $this['timestamp'] = time();
        }
        return $this['timestamp'];
    }
}