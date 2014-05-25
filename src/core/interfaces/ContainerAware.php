<?php

namespace Stark\core\interfaces;
use Stark\core\Container;


interface ContainerAware {

    public function setContainer(Container $container);

}