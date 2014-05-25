<?php
namespace Stark\tasks;

use Stark\core\tasks\Task;

class Mail extends Task{
    private $to;
    private $from;
    private $subject;
    private $body;


    public function setTo($to) {
        $this->to = $to;
    }
    public function setFrom($from) {
        $this->from = $from;
    }
    public function setSubject($subject) {
        $this->subject = $subject;
    }
    public function setBody($body) {
        $this->body = $body;
    }

    public function getName() {
        return 'Mail notification';
    }

    public function execute() {
        mail($this->to, $this->subject, $this->body);
    }
}