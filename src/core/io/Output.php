<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 04/05/14
 * Time: 4:44 PM
 */
namespace Stark\core\io;

class Output {

    private $prefix = '';

    public function setPrefix($prefix) {
        $this->prefix = $prefix;
    }

    public function write($text) {
        echo $text;
    }


    public function writeLn($text) {
        echo $this->prefix . $text . PHP_EOL;
    }
}