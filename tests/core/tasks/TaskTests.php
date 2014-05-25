<?php

/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 04/05/14
 * Time: 2:26 PM
 */

class TaskTests extends \PHPUnit_Framework_TestCase{


    public function testIsSuccessful() {
        $task = $this->getMockBuilder('Stark\core\tasks\Task')->getMockForAbstractClass();
        $this->assertEquals(true, $task->isSuccessful());
        $task->pushError('error1');
        $this->assertEquals(false, $task->isSuccessful());
        $task->pushError('error2');
        $this->assertEquals(false, $task->isSuccessful());
    }

    public function testGetErrorsCall() {
        $task = $this->getMockBuilder('Stark\core\tasks\Task')->getMockForAbstractClass();
        $task->pushError('error1');
        $task->pushError('error2');
        $this->assertEquals(array('error1', 'error2'), $task->getErrors());
    }

    public function testParamIsTrue() {
        $task = $this->getMockBuilder('Stark\core\tasks\Task')->getMockForAbstractClass();
        $this->assertEquals(false, $task->paramIsTrue('false'));
        $this->assertEquals(false, $task->paramIsTrue('FALSE'));
        $this->assertEquals(false, $task->paramIsTrue(''));
        $this->assertEquals(false, $task->paramIsTrue('0'));
        $this->assertEquals(false, $task->paramIsTrue('no'));
        $this->assertEquals(false, $task->paramIsTrue('NO'));

        $this->assertEquals(true, $task->paramIsTrue('yes'));
        $this->assertEquals(true, $task->paramIsTrue('YES'));
        $this->assertEquals(true, $task->paramIsTrue('true'));
        $this->assertEquals(true, $task->paramIsTrue('TRUE'));
        $this->assertEquals(true, $task->paramIsTrue('TruE'));
        $this->assertEquals(true, $task->paramIsTrue('1'));
    }

}