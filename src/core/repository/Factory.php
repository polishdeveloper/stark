<?php
namespace Stark\core\repository;

final class Factory
{

    private $repoToClassNameMap = array(
        'svn' => '\Stark\core\repository\SVN'
    );


    public function registerRepository($name, $className, &$errorMessage) {
        if (array_key_exists($name, $this->repoToClassNameMap)) {
            $errorMessage = "Repository $name already exists";
            return false;
        }
        if (!class_exists($className)) {
            $errorMessage = "Class $className doesn't exist";
        }
        $this->repoToClassNameMap[$name] = $className;
    }


    public function getRepository($type, array $arguments) {
        $type = mb_strtolower($type);

        if (!array_key_exists($type, $this->repoToClassNameMap)) {
            throw new \InvalidArgumentException("Unknown repository type $type");
        }

        $className = $this->repoToClassNameMap[$type];

        if (class_exists($className)) {
            $reflectionClass = new \ReflectionClass($className);
            return $reflectionClass->newInstanceArgs($arguments);

        } else {
            throw new \InvalidArgumentException("Reposistory '$type' doesn't exist ");
        }
    }
}