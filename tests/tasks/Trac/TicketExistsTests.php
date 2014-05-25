<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 22/05/14
 * Time: 6:13 AM
 */
class TicketExistsTests extends PHPUnit_Framework_TestCase {

    private function createTaskWithMockedRepo($comment) {
        $repoMock = $this->getMockBuilder('Stark\core\Repository')->getMock();
        $repoMock->expects($this->once())
            ->method('getComment')
            ->will($this->returnValue($comment));

        $container = new \Stark\core\Container();
        $container->setRepo($repoMock);


        $task = new \Stark\tasks\Trac\TicketExists();
        $task->setContainer($container);
        return $task;
    }


    public function testFailsForEmptyMessage() {
        $task = $this->createTaskWithMockedRepo('');
        $task->execute();
        $this->assertEquals(false, $task->isSuccessful());
    }


    public function testFailsForMessageWithoutTicket() {
        $task = $this->createTaskWithMockedRepo('test message');
        $task->execute();
        $this->assertEquals(false, $task->isSuccessful());
    }

    public function testFailsForMessageWithInvalidTicket() {
        $task = $this->createTaskWithMockedRepo('Fix for #12abc');
        $task->execute();
        $this->assertEquals(false, $task->isSuccessful());
    }

    public function testSucceedForMessageWithTicket() {
        $task = $this->createTaskWithMockedRepo('Fix for #1234');
        $task->execute();
        $this->assertEquals(false, $task->isSuccessful());
    }











}