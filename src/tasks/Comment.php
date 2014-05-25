<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 15/05/14
 * Time: 12:55 AM
 */
namespace Stark\tasks;

use Stark\core\tasks\Task;

class Comment extends Task{
    private $minLength = 0;
    private $notEmpty  = 'true';
    private $regex     = false;


    public function getName() {
        return 'Comment Validator';
    }

    public function setMinLength($minLength) {
        if ($minLength < 0) {
            throw new \InvalidArgumentException('Text Length cannot be lower than zero');
        }
        if ((string)(int)$minLength !== (string)$minLength) {
            throw new \InvalidArgumentException('Invalid argument, expecting number');
        }

        $this->minLength = $minLength;
    }
    public function setNotEmpty($hasToNotEmpty) {
        $this->notEmpty = $hasToNotEmpty;
    }

    public function setRegex($regex) {
        if (@preg_match($regex, null) === false) {
            throw new \InvalidArgumentException('Invalid regex');
        }
        $this->regex = $regex;
    }



    public function execute() {
        $comment = $this->container->getRepo()->getComment();

        $this->validateNotEmpty($comment);
        $this->validateLength($comment);
        $this->validateByRegex($comment);
    }


    protected function validateNotEmpty($comment) {
        if ($this->paramIsTrue($this->notEmpty) && empty($comment)) {
            $this->pushError('Commit message cannot be empty');
        }
    }

    protected function validateByRegex($comment) {
        if ($this->regex !== false) {
            if (preg_match($this->regex, $comment) == 0) {
                $this->pushError("Task doesn't match expected regex: {$this->regex}");
            }
        }
    }

    /**
     * @param string $comment
     */
    public function validateLength($comment)
    {
        $textLength = mb_strlen($comment);
        if ($this->minLength > 0) {
            if ($textLength < $this->minLength) {
                $this->pushError("Expected at least {$this->minLength} characters long comment. Got $textLength chars");
            }
        }
    }

}