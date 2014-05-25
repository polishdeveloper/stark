<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 25/05/14
 * Time: 1:51 AM
 */
namespace Stark\core\interfaces;

interface BugTracker {

    public function extractTicketId($message);
    public function isTicketValid($ticketId);
    public function postComment($ticketId, $comment);

}