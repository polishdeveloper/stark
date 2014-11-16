<?php

/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 04/05/14
 * Time: 2:26 PM
 */
require(__DIR__ . '/../../../src/core/tasks/Factory.php');
use Stark\core\tasks\Factory;


class testTask {

    public function setMinLength($param) {

    }
    public function setNotEmpty($param) {

    }

}

class FactoryTests extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Factory;
     */
    private $factory;

    public function setUp() {
        $this->factory = new Factory();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInitializationOfUndefinedTask() {
        $task = $this->factory->buildTask('undefined', array());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCallingNotExistingSetter() {
        $taskMock = $this->getMock('testTask', array('setContainer'));

        $factoryMock = $this->getMock('\Stark\core\tasks\Factory', array('createTaskInstance'));
        $factoryMock->expects($this->once())
            ->method('createTaskInstance')
            ->with('test')
            ->will($this->returnValue($taskMock));

        $factoryMock->buildTask('test', array('dummyVariable' => 15));
    }

    public function testInitializationOfNewTask() {
        $taskMock = $this->getMock('testTask', array('setMinLength', 'setNotEmpty', 'setContainer'));
        $taskMock->expects($this->once())
            ->method('setMinLength')
            ->with(5)
            ->will($this->returnValue(true));
        $taskMock->expects($this->once())
            ->method('setNotEmpty')
            ->with('true')
            ->will($this->returnValue(true));

        $factoryMock = $this->getMock('\Stark\core\tasks\Factory', array('createTaskInstance'));
        $factoryMock->expects($this->once())
            ->method('createTaskInstance')
            ->with('test')
            ->will($this->returnValue($taskMock));


        $factoryMock->buildTask('test', array('minLength' => 5, 'notEmpty' => 'true'));
    }

}