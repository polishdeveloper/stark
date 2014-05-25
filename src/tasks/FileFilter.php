<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 22/05/14
 * Time: 12:07 PM
 */
namespace Stark\tasks;

use Stark\core\tasks\Task;

class FileFilter extends Task {

    private $regex = false;
    private $restrictedExtensions = false;
    private $admin = array();


    public function setExtensions($restrictedExtensions) {
        $this->restrictedFiles = explode(',', $restrictedExtensions);
    }

    public function setRegex($regex) {
        $this->regex = $regex;
    }

    public function setAdmin($admins) {

        $this->admin = explode(',', $admins);
    }

    public function getName() {
        return 'File filter';
    }

    public function execute() {
        if (!in_array($this->container->getRepo()->getAuthor(), $this->admin)) {
            $filteredFiles = array();

            if ($this->restrictedExtensions) {
                foreach ($this->filterByExtension() as $file) {
                    $this->pushError("Cannot proceed file {$file->getPath()}, given extension is disabled");
                }
            }
            if ($this->regex !== false) {
                foreach ($this->filterByRegex() as $file) {
                    $this->pushError("Cannot proceed file {$file->getPath()}, file matches regex '{$this->regex}'");
                }
            }
        }
    }

    private function filterByExtension() {
        $files = array();
        foreach ($this->container->getRepo()->getChangedFilesCollection() as $file) {
            if (in_array($file->getExtension(), $this->restrictedExtensions)) {
                $files[] = $file;
            }
        }
        return $files;
    }

    private function filterByRegex() {
        $files = array();

        foreach ($this->container->getRepo()->getChangedFilesCollection() as $file) {
            if (preg_match_all($this->regex, $file->getPath()) != 0) {
                $files[] = $file;
            }
        }
        return $files;
    }


}