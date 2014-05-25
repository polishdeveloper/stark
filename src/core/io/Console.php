<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 05/05/14
 * Time: 12:16 AM
 */
namespace Stark\core\io;

class Console {

    public function execute($command, &$returnValue = null) {
        ob_start();
        passthru($command, $returnValue);
        return ob_get_clean();
    }

}