<?php

use Stark\core\repository\SVN;
use Stark\core\io\File;

class SVNTests extends \PHPUnit_Framework_TestCase {

    private $commandToSampleOutputfile = array(
        'svnlook author -t txn repo' => 'svnlookAuthorOutput.log',
        'svnlook author -r rev repo' => 'svnlookAuthorOutput.log',
        'svnlook log -t txn repo' => 'svnlookCommentOutput.log',
        'svnlook log -r rev repo' => 'svnlookCommentOutput.log',
        'svnlook changed -t txn repo' => 'svnlookChangesOutput.log',
        'svnlook cat -t txn repo path_to_file' => 'svnlookCatOutput.log',
    );

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testIncorrectArgumentsCallOneArgument() {
        $svn = new SVN('one');
    }

    /**
     * @param $command
     * @param $count
     * @return PHPUnit_Framework_MockObject_MockObject|SVN
     */
    private function getRepoMock($command, $count, array $params, &$output = null) {
        $output = file_get_contents(__DIR__  . '/../../fixtures/repository/svn/' . $this->commandToSampleOutputfile[$command]);
        $mock = $this->getMock('Stark\core\repository\SVN', array('executeCommand'), $params);

        $mock->expects($count)
            ->method('executeCommand')
            ->with($command)
            ->will($this->returnValue($output));
        return $mock;
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testIncorrectArgumentsCallThreeArguments() {
        new SVN('one', 'two', 'three');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvavliadArgumentsCount()
    {
       new SVN('pre-commit');
    }
    public function testGetAuthorInPreCommit() {
        $mock = $this->getRepoMock('svnlook author -t txn repo', $this->once(), array('pre-commit', 'repo', 'txn'));
        $this->assertEquals('polish_developer', $mock->getAuthor());
    }

    public function testGetFileContent()
    {
        $mock = $this->getRepoMock('svnlook cat -t txn repo path_to_file', $this->once(), array('pre-commit', 'repo', 'txn'), $fileContent);
        $this->assertEquals($fileContent, $mock->getFileContent('path_to_file'));
    }

    public function testGetAuthorInPostCommit() {
        $mock = $this->getRepoMock('svnlook author -r rev repo', $this->once(), array('post-commit', 'repo', 'rev'));
        $this->assertEquals('polish_developer', $mock->getAuthor());
    }

    public function testGetCommentInPreCommit() {
        $mock = $this->getRepoMock('svnlook log -t txn repo', $this->once(), array('pre-commit', 'repo', 'txn'));
        $this->assertEquals('test comment', $mock->getComment());
    }

    public function testGetCommentInPostCommit() {
        $mock = $this->getRepoMock('svnlook log -r rev repo', $this->once(), array('post-commit', 'repo', 'rev'));
        $this->assertEquals('test comment', $mock->getComment());
    }


    public function testGetChangesFiles()
    {
        $output = file_get_contents(__DIR__  . '/../../fixtures/repository/svn/' . $this->commandToSampleOutputfile['svnlook changed -t txn repo']);
        $mock = $this->getMock('Stark\core\repository\SVN', array('executeCommand', 'createEmptyFilesCollection'), array('pre-commit', 'repo', 'txn'));
        $changedFilesCollection = $this->getMock('Stark\core\io\FilesCollection', array('addFile'));

        $mock->expects($this->once())
            ->method('executeCommand')
            ->with('svnlook changed -t txn repo')
            ->will($this->returnValue($output));

        $mock->expects($this->once())
            ->method('createEmptyFilesCollection')
            ->will($this->returnValue($changedFilesCollection));

        $changedFilesCollection->expects($this->at(0))
            ->method('addFile')
            ->with(new File(File::ADDED, File::NO_ACTION, 'addedFileOne', $mock));
        $changedFilesCollection->expects($this->at(1))
            ->method('addFile')
            ->with(new File(File::ADDED, File::NO_ACTION, 'addedFileTwo', $mock));
        $changedFilesCollection->expects($this->at(2))
            ->method('addFile')
            ->with(new File(File::MODIFIED, File::NO_ACTION, 'modifiedFileOne', $mock));
        $changedFilesCollection->expects($this->at(3))
            ->method('addFile')
            ->with(new File(File::DELETED, File::NO_ACTION,  'deletedFileOne', $mock));

        $mock->getChangedFilesCollection();
    }



    /**
     * @expectedException \RuntimeException
     */
    public function testRevisionTransactionNotPresent()
    {
        $svn = new SVN('post-lock', 'a', 'b');
        $svn->getChangedFilesCollection();
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testRevisionNotAvailable()
    {
        $svn = new SVN('pre-commit', 'a', 'b');
        $svn->getRevisionId();
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testTransactionNotAvailable()
    {
        $svn = new SVN('post-commit', 'a', 'b');
        $svn->getTransactionId();
    }
}