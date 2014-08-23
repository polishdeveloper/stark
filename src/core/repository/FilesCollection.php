<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 22/05/14
 * Time: 12:20 PM
 */
namespace Stark\core\repository;

use Stark\core\io\File;

class FilesCollection implements  \ArrayAccess, \Iterator{

    private $position = 0;
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
                $filesCollection[] = $file;
            }
        }
        return $filesCollection;
    }


    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->files);
    }

    public function offsetGet($offset)
    {
        return $this->files[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->addFile($value);
    }


    public function offsetUnset($offset)
    {
        unset($this->files[$offset]);
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