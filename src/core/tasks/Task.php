<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 15/05/14
 * Time: 12:56 AM
 */
namespace Stark\core\tasks;

use Stark\core\Container;
use Stark\core\Properties;

abstract class Task {
    protected $errors = array();

    /**
     * @var Container
     */
    protected $container;

    public function setContainer(Container $container) {
        $this->container = $container;
    }

    public function pushError($error) {
        $this->errors[] = $error;
    }

    public function paramIsTrue($paramValue) {
        $paramValue = (string)strtolower($paramValue);
        return $paramValue != '' && $paramValue != 'no' && $paramValue != 'false' && $paramValue != '0';
    }

    public function isSuccessful() {
        return empty($this->errors);
    }

    public function getErrors() {
        return $this->errors;
    }

    abstract function getName();
    abstract function execute();

}