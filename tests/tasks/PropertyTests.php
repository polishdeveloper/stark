<?php

/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 04/05/14
 * Time: 2:26 PM
 */

class PropertyTest extends \PHPUnit_Framework_TestCase{

    /**
     * @var \Stark\core\Properties
     */
    private $propertyTask;

    protected function setUp() {
        $this->propertyTask = new \Stark\tasks\Property();
    }

    public function testSetProperty() {
        $container = new \Stark\core\Container();

        $propertiesMock = $this->getMock('\Stark\core\Properties', array('set'));
        $propertiesMock->expects($this->once())
            ->method('set')
            ->with('test', 'abc');
        $container->setProperties($propertiesMock);

        $this->propertyTask->setContainer($container);
        $this->propertyTask->setName('test');
        $this->propertyTask->setValue('abc');
        $this->propertyTask->execute();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testOmmitingNameFails() {
        $container = new \Stark\core\Container();

        $propertiesMock = $this->getMock('\Stark\core\Properties', array('set'));
        $propertiesMock->expects($this->never())
            ->method('set');
        $container->setProperties($propertiesMock);


        $this->propertyTask->setContainer($container);
        $this->propertyTask->setValue('test');
        $this->propertyTask->execute();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWillFailWithEmptyName() {
        $this->propertyTask->setName('');
    }


}