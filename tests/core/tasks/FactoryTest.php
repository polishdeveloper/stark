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
    public function setContainer($container)
    {

    }
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

    public function testCreationOfNewTask()
    {
        $taskFactory = new Factory();
        $result = $taskFactory->registerTask('test', '\testTask', $error_message);
        $myTask = $taskFactory->buildTask('test', array());
        $this->assertInstanceOf('testTask', $myTask);
        $this->assertEquals(true, $result);
    }

    public function testValidTaskRegistration()
    {
        $taskFactory = new Factory();
        $error_message = false;
        $result = $taskFactory->registerTask('test', '\testTask', $error_message);
        $this->assertEquals(false, $error_message);
        $this->assertEquals(true, $result);
    }

    public function testMissingTaskRegistration()
    {
        $taskFactory = new Factory();
        $error_message = false;
        $result = $taskFactory->registerTask('test', '\testTask2', $error_message);
        $this->assertNotEquals(false, $error_message);
        $this->assertEquals(false, $result);
    }

    public function testDoubleTaskTaskRegistration()
    {
        $taskFactory = new Factory();
        $error_message = false;
        $result = $taskFactory->registerTask('test', '\testTask', $error_message);
        $this->assertEquals(false, $error_message);
        $this->assertEquals(true, $result);
        $result = $taskFactory->registerTask('test', '\testTask', $error_message);
        $this->assertNotEquals(false, $error_message);
        $this->assertEquals(false, $result);
    }

}