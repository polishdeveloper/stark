<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 04/05/14
 * Time: 2:26 PM
 */
use Stark\core\io\File;

class FilesCollectionTest extends \PHPUnit_Framework_TestCase {


    private function getFileMock($action, $path)
    {
        $mock = $this->getMock('Stark\core\io\File', array('getOperation', 'getPath'), array(), '', false);
        $mock->expects($this->any())
            ->method('getOperation')
            ->will($this->returnValue($action));
        $mock->expects($this->any())
            ->method('getPath')
            ->will($this->returnValue($path));
        return $mock;
    }

    public function testCountable()
    {
        $collection = new \Stark\core\io\FilesCollection();
        $this->assertCount(0, $collection);
        $collection->addFile($this->getFileMock(File::ADDED, 'file1.txt'));
        $this->assertCount(1, $collection);
        $collection->addFile($this->getFileMock(File::MODIFIED, 'file2.txt'));
        $this->assertCount(2, $collection);
    }


    public function testIterator()
    {
        $collection = new \Stark\core\io\FilesCollection();
        $mock1 = $this->getFileMock(File::ADDED, 'file1.txt');
        $mock2 = $this->getFileMock(File::ADDED, 'file2.txt');


        $collection->addFile($mock1);
        $collection->addFile($mock2);
        $this->assertEquals(0, $collection->key());
        $this->assertEquals(true, $collection->valid());
        $this->assertEquals($mock1, $collection->current());
        $this->assertEquals($mock1, $collection->current());
        $collection->next();
        $this->assertEquals(true, $collection->valid());
        $this->assertEquals(1, $collection->key());
        $this->assertEquals($mock2, $collection->current());
        $collection->next();
        $this->assertEquals(false, $collection->valid());
        $collection->rewind();
        $this->assertEquals(0, $collection->key());
        $this->assertEquals(true, $collection->valid());
        $this->assertEquals($mock1, $collection->current());
    }

    public function testGetFiltered()
    {
        $repoMock = $this->getMock('Stark\core\Repository', array('getFileContent', 'getComment', 'getAuthor', 'getChangedFilesCollection' ));

        $collection = new \Stark\core\io\FilesCollection();
        $collection->addFile($this->getFileMock(File::ADDED, 'file1.txt'));
        $collection->addFile($this->getFileMock(File::MODIFIED, 'file2.txt'));
        $collection->addFile($this->getFileMock(File::ADDED, 'file3.txt'));


        $addedFiles = $collection->getAddedFiles();
        $this->assertCount(2, $addedFiles);
        $file1 = $addedFiles->current();
        $addedFiles->next();
        $file2 = $addedFiles->current();

        $this->assertEquals('file1.txt', $file1->getPath());
        $this->assertEquals('file3.txt', $file2->getPath());

        $this->assertCount(1, $collection->getModifiedFiles());
        $this->assertCount(0, $collection->getDeletedFiles());
    }





}
