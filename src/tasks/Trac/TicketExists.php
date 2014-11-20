<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 15/05/14
 * Time: 12:55 AM
 */
namespace Stark\tasks\Trac;

use Stark\core\tasks\Task;

class TicketExists extends Task{

    private $regex     = '/#[0-9]+ /';


    public function getName() {
        return 'Trac ticket';
    }

    public function execute()
    {
        $comment = $this->getContainer()->getRepo()->getComment();
        if (preg_match($this->regex, $comment) == 0) {
            $this->pushError("Trac ticket not provided");
        }
    }

}