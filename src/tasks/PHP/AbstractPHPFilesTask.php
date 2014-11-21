<?php
namespace Stark\tasks;

use Stark\core\io\File;
use Stark\core\tasks\Task;

abstract class AbstractPHPFilesTask extends Task{

    private $phpFileExtensions = array('php', 'php4', 'php5', 'phtml');

    public function setFileExtensions($extensions)
    {
        $this->phpFileExtensions = explode(',', $extensions);
    }


    public function execute()
    {
        /** @var File $file */
        foreach ($this->getContainer()->getRepo()->getChangedFilesCollection() as $file) {
            if (in_array($file->getExtension(), $this->phpFileExtensions) && $file->getOperation() != File::DELETED) {
                $this->executeOnPHPFile($file);
            }
        }
    }

    abstract protected function executeOnPHPFile(File $file);

}