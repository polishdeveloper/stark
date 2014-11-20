<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 15/05/14
 * Time: 12:56 AM
 */
namespace Stark\core\tasks;

use Stark\core\Container;

abstract class Task {
    protected $errors = array();

    /**
     * @var Container
     */
    private $container;

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

    /**
     * @return string
     */
    protected function expandVariable($message) {
        return $this->container->getProperties()->expand($message);
    }

    abstract function getName();
    abstract function execute();
}