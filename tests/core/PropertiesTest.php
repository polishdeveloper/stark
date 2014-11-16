<?php

use Stark\core\Properties;

class PropertiesTest extends \PHPUnit_Framework_TestCase {

    public function testSimpleGetAndSet() {
        $properties = new Properties();
        $properties->set('test', 123);
        $this->assertEquals($properties->get('test'), 123);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSettingEmptyProperty() {
        $properties = new Properties();
        $properties->set('', '');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetUnknownProperty() {
        $properties = new Properties();
       $properties->get('test');
    }


    public function testComplexGet() {
        $properties = new Properties();
        $properties->set('abc', '123');
        $properties->set('A', '${abc}');
        $properties->set('B', 'ABC ${abc} DEF');
        $properties->set('C', 'ABC ${abc} DEF ${abc}');
        $properties->set('D', 'ABC${abc}DEF${abc}');
        $properties->set('E', '${A}|${abc}');
        $properties->set('F', '${A}.${abc}');
        $properties->set('G', '.${A}.${abc}.');
        $this->assertEquals($properties->get('abc'), '123');
        $this->assertEquals($properties->get('A'), '123');
        $this->assertEquals($properties->get('B'), 'ABC 123 DEF');
        $this->assertEquals($properties->get('C'), 'ABC 123 DEF 123');
        $this->assertEquals($properties->get('D'), 'ABC123DEF123');
        $this->assertEquals($properties->get('E'), '123|123');
        $this->assertEquals($properties->get('F'), '123.123');
        $this->assertEquals($properties->get('G'), '.123.123.');



        $properties->set('ab1', '1');
        $properties->set('ab2', '${ab1}2');
        $properties->set('ab3', '${ab2}3');
        $properties->set('ab4', '${ab3}4');
        $properties->set('ab5', '${ab1}${ab4}${ab4}${ab1}');
        $this->assertEquals($properties->get('ab1'), '1');
        $this->assertEquals($properties->get('ab2'), '12');
        $this->assertEquals($properties->get('ab3'), '123');
        $this->assertEquals($properties->get('ab4'), '1234');
        $this->assertEquals($properties->get('ab5'), '1123412341');


        $properties->set('aa', 'TEST');
        $properties->set('ab', 'aa');
        $properties->set('ac', '$${aa}$');
        $properties->set('ad', '{${aa}}');
        $properties->set('ae', '${${ab}}');
        $this->assertEquals($properties->get('ac'), '$TEST$');
        $this->assertEquals($properties->get('ad'), '{TEST}');
        $this->assertEquals($properties->get('ae'), '${aa}');

    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCycleA() {
        $properties = new Properties();
        $properties->set('a', '${b}');
        $properties->set('b', '${a}');
        $properties->get('a');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCycleB() {
        $properties = new Properties();
        $properties->set('b', '12${b}');
        $properties->get('b');
    }


    public function testLazyPropps() {
        $response = 'response';

        $callable = function() use ($response) {
            return $response;
        };

        $properties = new Properties();
        $properties->set('test', $callable);
        $this->assertEquals('response', $properties->get('test'));
        $cachedResponse = $response;
        $this->assertEquals($cachedResponse, $properties->get('test'));
    }


    public function testinitializeDefault()
    {
        $time = time();

        $properties = new Properties();

        $container = new \Stark\core\Container();
        $container['timestamp'] = $time;
        $repoMock = $this->getMock('Stark\core\Repository', array('getFileContent', 'getComment', 'getAuthor', 'getChangedFilesCollection'));
        $repoMock->expects($this->once())
            ->method('getComment')
            ->will($this->returnValue('message'));
        $repoMock->expects($this->once())
            ->method('getAuthor')
            ->will($this->returnValue('author'));
        $container->setRepo($repoMock);



        $properties->setContainer($container);
        $properties->initializeDefault();

        $this->assertEquals($properties->get('timestamp'), $time);
        $this->assertEquals($properties->get('author'), 'author');
        $this->assertEquals($properties->get('message'), 'message');

        $this->assertEquals($properties->get('author'), 'author');
        $this->assertEquals($properties->get('message'), 'message');
    }


}