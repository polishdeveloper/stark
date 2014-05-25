<?php

use Stark\core\repository\SVN;

class SVNTests extends \PHPUnit_Framework_TestCase {

    private $commandToSampleOutputfile = array(
        'svnlook author -t 1 test' => 'svnlookAuthorOutput.log',
        'svnlook log -t 1 test' => 'svnlookCommentOutput.log',
        'svnlook changed -t 1 test' => 'svnlookChangesOutput.log',
        'svnlook cat -t 1 testFile' => 'svnlookCatOutput.log',
    );

    /**
     * @param $command
     * @param $count
     * @return PHPUnit_Framework_MockObject_MockObject|SVN
     */
    private function getRepoMock($command, $count) {
        $output = file_get_contents(__DIR__  . '/../../fixtures/repository/svn/' . $this->commandToSampleOutputfile[$command]);
        $mock = $this->getMock('Stark\core\repository\SVN', array('executeCommand'), array(1, 'test'));

        $mock->expects($count)
            ->method('executeCommand')
            ->with($command)
            ->will($this->returnValue($output));
        return $mock;
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testIncorrectArgumentsCallOneArgument() {
        $svn = new SVN(array('one'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testIncorrectArgumentsCallThreeArguments() {
        $svn = new SVN(array('one', 'two', 'three'));
    }


    public function testGetAuthor() {
        $mock = $this->getRepoMock('svnlook author -t 1 test', $this->once());
        $this->assertEquals('polish_developer', $mock->getAuthor());
    }

    public function testGetComment() {
        $mock = $this->getRepoMock('svnlook log -t 1 test', $this->once());
        $this->assertEquals('test comment', $mock->getComment());
    }


    public function testGetChangesFiles() {
        $mock = $this->getRepoMock('svnlook changed -t 1 test', $this->once());
        $changedFiles = array(
            new \Stark\core\io\File(\Stark\core\io\File::ADDED, 'addedFileOne', $mock),
            new \Stark\core\io\File(\Stark\core\io\File::ADDED, 'addedFileTwo', $mock),
            new \Stark\core\io\File(\Stark\core\io\File::MODIFIED, 'modifiedFileOne', $mock),
            new \Stark\core\io\File(\Stark\core\io\File::DELETED, 'deletedFileOne', $mock),
        );

        $addedFiles = array($changedFiles[0], $changedFiles[1]);
        $modifiedFiles = array($changedFiles[2]);
        $deletedFiles = array($changedFiles[3]);


        $this->assertEquals($changedFiles, $mock->getChangedFiles());
        $this->assertEquals($addedFiles, $mock->getAddedFiles());
        $this->assertEquals($modifiedFiles, $mock->getModifiedFiles());
        $this->assertEquals($deletedFiles, $mock->getDeletedFiles());

    }


}