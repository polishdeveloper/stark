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
            if (in_array($file->getExtension(), $this->phpFileExtensions) && $file->getType() != File::DELETED) {
                $this->checkSyntax($file);
            }
        }
    }

    private function checkSyntax(File $file) {
        exec("php -l '".escapeshellarg($file->getContent())."'" , $output, $returnVal);
        var_dump($returnVal, $output);
    }


}