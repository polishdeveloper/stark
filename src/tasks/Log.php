<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 25/05/14
 * Time: 2:16 AM
 */
namespace Stark\tasks;

use Stark\core\tasks\Task;

class Log extends Task {

    private $logFilename;
    private $message;

    public function getName() {
        return 'Logger';
    }

    public function setFile($filename) {
        $this->logFilename = $filename;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function execute() {
        $handle = fopen($this->expandVariable($this->logFilename), 'a+');
        if (!$handle) {
            return false;
        }
        fwrite($handle, $this->expandVariable($this->message) . PHP_EOL);
        fclose($handle);
        return true;
    }

}