<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 22/05/14
 * Time: 5:38 AM
 */
class CommentsTest extends PHPUnit_Framework_TestCase
{

    private function createCommentObjectWithMockedRepo($comment) {
        $repoMock = $this->getMockBuilder('Stark\core\Repository')->getMock();
        $repoMock->expects($this->once())
            ->method('getComment')
            ->will($this->returnValue($comment));

        $container = new \Stark\core\Container();
        $container->setRepo($repoMock);


        $task = new \Stark\tasks\Comment();
        $task->setContainer($container);
        return $task;
    }


    public function testNotEmptyFailsWithEmpty() {
        $task = $this->createCommentObjectWithMockedRepo('');
        $task->setNotEmpty(true);
        $task->execute();
        $this->assertEquals(false, $task->isSuccessful());
    }


    public function testNotEmptySuccessWithCorrectComment() {
        $task = $this->createCommentObjectWithMockedRepo('test');
        $task->setNotEmpty(true);
        $task->execute();
        $this->assertEquals(true, $task->isSuccessful());
    }

    public function testNotEmptySuccedWhenDisabled() {
        $task = $this->createCommentObjectWithMockedRepo('');
        $task->setNotEmpty(false);
        $task->execute();
        $this->assertEquals(true, $task->isSuccessful());

        $task = $this->createCommentObjectWithMockedRepo('test');
        $task->setNotEmpty(false);
        $task->execute();
        $this->assertEquals(true, $task->isSuccessful());
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetLenghtFailsForNegativeNumbers() {
        $task = new \Stark\tasks\Comment();
        $task->setMinLength(-1);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetLenghtFailsForEmptyStringAsLenght() {
        $task = new \Stark\tasks\Comment();
        $task->setMinLength('');
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetLengthFailsForNaNAsLenght() {
        $task = new \Stark\tasks\Comment();
        $task->setMinLength('abc');
    }



    public function testLengthSucceedFor0CharsValidationForNotEmptyComment()
    {
        $task = $this->createCommentObjectWithMockedRepo('');
        $task->setNotEmpty(false);
        $task->setMinLength(0);
        $task->execute();
        $this->assertEquals(true, $task->isSuccessful());
    }


    public function testLengthSucceedFor0CharsValidationForEmptyComment()
    {
        $task = $this->createCommentObjectWithMockedRepo('');
        $task->setNotEmpty(false);
        $task->setMinLength(0);
        $task->execute();
        $this->assertEquals(true, $task->isSuccessful());
    }


    public function testLengthSucceedForTextsShorterOrEqual() {
        $task = $this->createCommentObjectWithMockedRepo('0123456789');
        $task->setMinLength(5);
        $task->execute();
        $this->assertEquals(true, $task->isSuccessful());


        $task = $this->createCommentObjectWithMockedRepo('0123456789');
        $task->setMinLength(9);
        $task->execute();
        $this->assertEquals(true, $task->isSuccessful());

        $task = $this->createCommentObjectWithMockedRepo('0123456789');
        $task->setMinLength(10);
        $task->execute();
        $this->assertEquals(true, $task->isSuccessful());
    }


    public function testLengthFailsForTextsShorterOrEqual() {
        $task = $this->createCommentObjectWithMockedRepo('0123456789');
        $task->setMinLength(11);
        $task->execute();
        $this->assertEquals(false, $task->isSuccessful());
    }

}