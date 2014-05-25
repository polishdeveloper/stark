<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 15/05/14
 * Time: 3:20 PM
 */
namespace Stark\core;

use Stark\core\tasks\Factory;

class Container extends \Pimple\Container {

    /**
     * @return Factory
     */
    public function getTasksFactory() {
        return $this['tasksFactory'];
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

}