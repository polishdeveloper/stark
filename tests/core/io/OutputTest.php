<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 04/05/14
 * Time: 2:26 PM
 */

class OutputTest extends \PHPUnit_Framework_TestCase {

    public function testSingleOutput()
    {
        $text = 'my new text';
        ob_start();
        $output = new \Stark\core\io\Output();
        $output->write($text);
        $this->assertEquals($text, ob_get_clean());

    }

    public function testMultiOutput()
    {
        $text_a = 'my new text';
        $text_b = 'second text';

        ob_start();
        $output = new \Stark\core\io\Output();
        $output->write($text_a);
        $output->write($text_b);
        $this->assertEquals($text_a . $text_b, ob_get_clean());

    }


    public function testOutputWithNewLine()
    {
        $text_a = 'my new text';
        $text_b = 'second text';

        ob_start();
        $output = new \Stark\core\io\Output();
        $output->writeLn($text_a);
        $output->writeLn($text_b);
        $this->assertEquals($text_a . PHP_EOL . $text_b . PHP_EOL, ob_get_clean());
    }

    public function testOutputWithPrefix()
    {
        $text_a = 'my new text';
        $text_b = 'second text';

        ob_start();
        $output = new \Stark\core\io\Output();
        $output->setPrefix('test:');
        $output->writeLn($text_a);
        $output->writeLn($text_b);
        $this->assertEquals('test:' . $text_a . PHP_EOL . 'test:' . $text_b . PHP_EOL, ob_get_clean());
    }


}
