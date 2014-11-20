<?php
namespace Stark\tasks;

use Stark\core\tasks\Task;

class UpdateComposerLock extends Task
{
    /**
     * @var array
     */
    private $composerLockData;
    private $packageIdx;


    private $lockFilePath;
    private $packageName;

    public function setLockFilePath($lockFilePath) {
        if (!file_exists($lockFilePath)) {
            throw new \RuntimeException('Cannot locate file ' . $lockFilePath);
        }
        $this->lockFilePath = $lockFilePath;
    }
    public function setPackageName($packageName) {
        $this->packageName = $packageName;
    }


    function getName()
    {
        return 'UpdateComposerLock';
    }

    function execute()
    {
        $this->loadComposerLockData();
        $package = $this->getPackage();
        if ($package === false) {
            throw new \RuntimeException('Package not found');
        }
        $lastRevision = $this->fetchLastRevision();
        $this->updateComposerLockData($lastRevision);
        $this->writeComposerLockData();

    }


    private function updateComposerLockData($reference) {
        //TODO - redo to work with something different than git
        $this->composerLockData[self::PACKAGES_IDX][$this->packageIdx]['source']['reference'] = $reference;
    }

    private function getPackage() {
        foreach($this->composerLockData['packages'] as $idx => $package) {
            if ($package['name'] == $this->packageName) {
                $this->packageIdx = $idx;
                return $package;
            }
        }
        return false;
    }

    private function loadComposerLockData() {
        $this->composerLockData = json_decode(file_get_contents($this->lockFilePath), true);
    }

    private function writeComposerLockData() {
        file_put_contents($this->lockFilePath, json_encode($this->composerLockData));
    }


    private function fetchLastRevision() {
        $repo = $this->getContainer()->getRepo();
        return $repo->getRevisionId();
    }


}