<?php
namespace Stark\tasks;

use Stark\core\tasks\Task;

class Mail extends Task{
    private $to;
    private $from = 'stark@localhost';
    private $replyTo = 'none@localhost';
    private $subject;
    private $body;


    public function setTo($to) {
        $this->to = $to;
    }
    public function setFrom($from) {
        $this->from = $from;
    }
    public function setReplyTo($replyTo) {
        $this->replyTo = $replyTo;
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
        $headers = 'From: ' . $this->from .  "\r\n" .
            'Reply-To: ' . $this->replyTo .  "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail($this->to, $this->subject, $this->body, $headers);
    }
}