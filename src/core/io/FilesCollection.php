<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 22/05/14
 * Time: 12:20 PM
 */
namespace Stark\core\io;

class FilesCollection implements \Iterator, \Countable{

    private $position = 0;
    /**
     * @var File[]
     */
    private $files;

    public function addFile(File $file) {
        $this->files[] = $file;
    }

    public function getAddedFiles() {
        return $this->getFilteredFilesByAction(File::ADDED);
    }
    public function getModifiedFiles() {
        return $this->getFilteredFilesByAction(File::MODIFIED);
    }
    public function getDeletedFiles() {
        return $this->getFilteredFilesByAction(File::DELETED);
    }

    private function getFilteredFilesByAction($action) {
        $filesCollection = new FilesCollection();
        foreach($this->files as $file) {
            if ($file->getOperation() === $action) {
                $filesCollection->addFile($file);
            }
        }
        return $filesCollection;
    }

    public function count()
    {
        return count($this->files);
    }

    public function current()
    {
        return $this->files[$this->position];
    }

    public function next()
    {
        $this->position++;
    }

    public function key()
    {
        return $this->position;
    }

    public function valid()
    {
        return $this->position < count($this->files);
    }

    public function rewind()
    {
        $this->position = 0;
    }
}