<?php

namespace Stark\core;

trait ContainerAwareTrait
{
    /**
     * @var Container
     */
    private $container;


    public function setContainer(Container $container) {
        $this->container = $container;
    }

    /**
     * @return Container
     */
    protected function getContainer()
    {
        return $this->container;
    }

}