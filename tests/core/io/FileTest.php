<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 04/05/14
 * Time: 2:26 PM
 */
use Stark\core\io\HooksXMLReader;

class FileTest extends \PHPUnit_Framework_TestCase {

    public function testFileCreation()
    {
        $repoMock = $this->getMock('Stark\core\Repository', array('getFileContent', 'getComment', 'getAuthor', 'getChangedFilesCollection' ));
        $file = new \Stark\core\io\File('a', 'b', 'path.ext', $repoMock);

        $this->assertEquals('a', $file->getOperation());
        $this->assertEquals('path.ext',$file->getPath());
        $this->assertEquals('ext', $file->getExtension());
    }

    public function testFileGetContent()
    {
        $repoMock = $this->getMock('Stark\core\Repository', array('getFileContent', 'getComment', 'getAuthor', 'getChangedFilesCollection'));
        $repoMock->expects($this->once())
            ->method('getFileContent')
            ->with('path.ext')
            ->will($this->returnValue('file_content'));

        $file = new \Stark\core\io\File('a', 'b', 'path.ext', $repoMock);

        ;
        $this->assertEquals('file_content', $file->getContent());
    }



}
