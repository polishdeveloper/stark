<?php
namespace Stark\core\repository;

use Stark\core\io\Console;
use Stark\core\io\File;
use Stark\core\Repository;

class SVN implements Repository{

    private $changedFilesCollection;

    public function __construct() {
        $arguments = func_get_args();

        if (count($arguments) != 2) {
            throw new \InvalidArgumentException('Bad number of arguments for SVN repo');
        }
        $this->repo = $arguments[1];
        $this->trx = $arguments[0];
    }

    public function getChangedFilesCollection() {
        if (null === $this->changedFilesCollection) {
            $this->changedFilesCollection = new FilesCollection();

            $elements = $this->executeCommand("svnlook changed -t {$this->trx} {$this->repo}");
            $byLine = explode("\n", $elements);

            foreach ($byLine as $changeDefinition) {
                $fileAction = mb_substr($changeDefinition, 0, 1);
                $dirAction = mb_substr($changeDefinition, 1, 1);
                $unused1 = mb_substr($changeDefinition, 2, 1);
                $unused2 = mb_substr($changeDefinition, 3, 1);
                $filePath = mb_substr($changeDefinition, 4);
                $this->changedFilesCollection->addFile(new File($fileAction, $filePath, $this));
            }
        }
        return $this->changedFilesCollection;
    }

    public function getFileContent($file) {
        return $this->executeCommand("svnlook cat -t {$this->trx} {$this->repo} $file");
    }

    public function getAuthor() {
        return trim($this->executeCommand("svnlook author -t {$this->trx} {$this->repo}"));
    }
    public function getComment() {
        return $this->executeCommand("svnlook log -t {$this->trx} {$this->repo}");
    }


    protected function executeCommand($command) {
        $console = new Console();
        return $console->execute($command);
    }
}