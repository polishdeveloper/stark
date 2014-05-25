<?php

use Stark\core\Stark;

class StarkTests extends \PHPUnit_Framework_TestCase {


    public function testSuccessfulInitialization() {
        $stark = new Stark(array('mock', 'pre-commit', 'testArg1', 'testArg2'));
        $stark->loadHooks(__DIR__ . '/../fixtures/hooksFiles/simple.xml');
        $stark->execute();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testUnsuccessfulInitializationUnknownFile() {
        $stark = new Stark(array('mock', 'pre-commit', 'testArg1', 'testArg2'));
        $stark->loadHooks(__DIR__ . '/../fixtures/hooksFiles/unknown.xml');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testUnsuccessfulInitializationWrongFile() {
        $stark = new Stark(array('mock', 'pre-commit', 'testArg1', 'testArg2'));
        $stark->loadHooks(__DIR__ . '/../fixtures/hooksFiles/invalid.xml');
    }




}