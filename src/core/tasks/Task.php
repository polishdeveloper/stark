<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 15/05/14
 * Time: 12:56 AM
 */
namespace Stark\core\tasks;

use Stark\core\ContainerAwareTrait;
use Stark\core\interfaces\ContainerAware;

abstract class Task implements ContainerAware{
    use ContainerAwareTrait;

    /**
     * @var array
     */
    protected $errors = array();

    /**
     * @param string $error
     */
    public function pushError($error)
    {
        $this->errors[] = $error;
    }

    public function paramIsTrue($paramValue)
    {
        $paramValue = (string)strtolower($paramValue);
        return $paramValue != '' && $paramValue != 'no' && $paramValue != 'false' && $paramValue != '0';
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return empty($this->errors);
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return string
     */
    protected function expandVariable($message) {
        return $this->container->getProperties()->expand($message);
    }

    /**
     * @return string
     */
    abstract function getName();

    /**
     * @return void
     */
    abstract function execute();
}