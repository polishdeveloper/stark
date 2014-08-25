<?php
namespace Stark\core\repository;

use Stark\core\Repository;

class GIT implements Repository{

    public function setArguments(array $arguments) {
        if (count($arguments) != 2) {
            throw new \InvalidArgumentException('Bad number of arguments for GIT repo');
        }
        $this->repo = $arguments[0];
        $this->trx = $arguments[1];
    }

    public function getComment() {

    }
    public function getModifiedFiles() {

    }
    public function getRemovedFiles() {

    }
    public function getAuthor() {

    }

    public function getFileContent($filePath)
    {

    }

    public function getChangedFilesCollection()
    {

    }
}