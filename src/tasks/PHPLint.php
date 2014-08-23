<?php
namespace Stark\tasks;

use Stark\core\io\File;
use Stark\core\tasks\Task;

class PHPLint extends Task{

    private $phpFileExtensions = array('php', 'php4', 'php5', 'phtml');

    public function setFileExtensions($extensions) {
        $this->phpFileExtensions = explode(',', $extensions);
    }

    public function getName() {
        return 'PHP Syntax check';
    }

    public function execute() {
        foreach ($this->container->getRepo()->getChangedFilesCollection() as $file) {
            if (in_array($file->getExtension(), $this->phpFileExtensions) && $file->getOperation() != File::DELETED) {
                $this->checkSyntax($file);
            }
        }
    }

    private function checkSyntax(File $file) {

        $command = "echo ".escapeshellarg($file->getContent()) . " | php -l 2>&1" ;
        exec($command, $output, $returnVal);
        if ($returnVal != 0) {
            $this->pushError(str_replace(' -', " " . $file->getPath(), $output[0]));
        }

    }


}