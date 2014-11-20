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

    /**
     * @var bool|array
     */
    private $regex = false;
    /**
     * @var bool|array
     */
    private $restrictedExtensions = false;
    /**
     * @var bool
     */
    private $useOnlyAsciiFileNames = false;
    /**
     * @var bool
     */
    private $noSpaces = false;

    private $admin = array();


    public function setExtensions($restrictedExtensions) {
        $this->restrictedExtensions = explode(',', $restrictedExtensions);
    }

    public function setRegex($regex) {
        $this->regex = $regex;
    }

    public function setAdmin($admins) {

        $this->admin = explode(',', $admins);
    }

    public function setNoSpaces($noSpaces)
    {
        $this->noSpaces = $noSpaces;
    }

    public function setUseOnlyAsciiFileNames($useOnlyAsciiFileNames)
    {
        $this->useOnlyAsciiFileNames = $useOnlyAsciiFileNames;
    }


    public function getName() {
        return 'File filter';
    }

    public function execute() {
        if (!in_array($this->getContainer()->getRepo()->getAuthor(), $this->admin)) {

            if ($this->noSpaces !== false) {
                $this->verifyByRegex('[ +]', ' file name contains spaces');
            }
            if ($this->useOnlyAsciiFileNames !== false) {
                $this->verifyByRegex('[^\x00-\x7F]', 'file name contains non-ASCII characters"');
            }
            if ($this->regex !== false) {
                $this->verifyByRegex($this->regex, 'file name matches regex ' . $this->regex);
            }
            if ($this->restrictedExtensions !== false) {
                $this->verifyProperExtensions();
            }
        }
    }

    protected function verifyProperExtensions()
    {
        foreach ($this->filterByExtension() as $file) {
            $this->pushError("Cannot proceed file {$file->getPath()}, given extension is disabled");
        }
    }

    private function filterByExtension() {
        $files = array();
        foreach ($this->getContainer()->getRepo()->getChangedFilesCollection() as $file) {
            if (in_array($file->getExtension(), $this->restrictedExtensions)) {
                $files[] = $file;
            }
        }
        return $files;
    }

    private function verifyByRegex($regex, $onMatchMessage) {
        foreach ($this->getContainer()->getRepo()->getChangedFilesCollection() as $file) {
            if (preg_match_all($regex, $file->getPath(), $matches) != 0) {
                $this->pushError("Cannot proceed file  {$file->getPath()} : " . $onMatchMessage);
            }
        }
    }




}