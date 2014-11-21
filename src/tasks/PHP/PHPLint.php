<?php
namespace Stark\tasks\PHP;

use Stark\core\io\File;
use Stark\tasks\AbstractPHPFilesTask;

class PHPLint extends AbstractPHPFilesTask{



    public function getName() {
        return 'PHP Syntax check';
    }


    protected function executeOnPHPFile(File $file) {

        $command = "echo ".escapeshellarg($file->getContent()) . " | php -l 2>&1" ;
        exec($command, $output, $returnVal);
        if ($returnVal == 255 && $output) {
            $this->pushError(str_replace(' -', " " . $file->getPath(), implode(',', $output)));
        }

    }


}