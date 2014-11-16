<?php

/**
* Created by PhpStorm.
* User: raynor
* Date: 04/05/14
* Time: 2:26 PM
*/
use Stark\core\io\HooksXMLReader;

class HooksXMLReaderTest extends \PHPUnit_Framework_TestCase {

    private function provideParser($file)
    {
        $parser = new HooksXMLReader();
        $parser->read($file);
        return $parser;
    }

    protected function getContainer() {
        return new \Stark\core\Container();
    }

    public function testParsing() {
        $reader = $this->provideParser(__DIR__ . '/../../fixtures/hooksFiles/valid.xml');
        $reader->parse();
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFileNotExist() {
        $this->provideParser('unknown_file');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidFile() {
        $hooks = $this->provideParser(__DIR__ . '/../../fixtures/hooksFiles/invalid.xml', $this->getContainer());
    }

    public function testSimpleXml() {
        $reader = $this->provideParser(__DIR__ . '/../../fixtures/hooksFiles/simple.xml', $this->getContainer());

        $expectedPreCommit = array(
            array(
                'name' => 'comment',
                'params' => array('minLength' => "5", 'notEmpty' => "true"),
            ),
            array(
                'name' => 'testHook',
                'params' => array('param' => "testParam"),
            ),
        );
        $expectedPostCommit = array(
            array(
                'name' => 'mail',
                'params' => array('to'  => 'admin@test.com', 'subject' => 'Post commit', 'body' => 'Valid commit'),
            ),
        );
        $this->assertEquals($expectedPostCommit, $reader->getHooks('post-commit'));
        $this->assertEquals($expectedPreCommit, $reader->getHooks('pre-commit'));

    }


}
