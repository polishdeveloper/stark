<?php
namespace Stark\core\repository;

use Stark\core\io\Console;
use Stark\core\io\File;
use Stark\core\io\FilesCollection;
use Stark\core\Repository;


/**
 * Class SVN
 * @package Stark\core\repository
 *
 * SVN HOOKS ARGUMENTS ORDER
 * start-commit REPOS-PATH USER CAPABILITIES TXN-NAME
 * pre-commit REPOS-PATH TXN-NAME
 * post-commit REPOS-PATH REVISION
 * pre-revprop-change REPOS-PATH REVISION USER PROPNAME ACTION
 * post-revprop-change REPOS-PATH REVISION USER PROPNAME ACTION
 * pre-lock REPOS-PATH PATH USER COMMENT STEAL
 * post-lock REPOS-PATH USER
 * pre-unlock REPOS-PATH PATH USER TOKEN BREAK-UNLOCK
 * post-unlock REPOS-PATH USER
 */
class SVN extends CommandlineRepo implements Repository{

    const START_COMMIT = 'start-commit';
    const PRE_COMMIT = 'pre-commit';
    const POST_COMMIT = 'post-commit';
    const PRE_REVPROP_CHANGE = 'pre-revprop-change';
    const POST_REVPROP_CHANGE = 'post-revprop-change';
    const PRE_LOCK = 'pre-lock';
    const POST_LOCK = 'post-lock';
    const PRE_UNLOCK = 'pre-unlock';
    const POST_UNLOCK = 'post-unlock';

    const REPOS_PATH = 'repos-path';
    const USER = 'user';
    const CAPABILITIES = 'capabilities';
    const TXN_NAME = 'txn-name';
    const PATH = 'path';
    const REVISION = 'revision';
    const PROPNAME = 'propname';
    const ACTION = 'action';
    const COMMENT = 'comment';
    const STEAL = 'steal';
    const TOKEN = 'token';
    const BREAK_UNLOCK = 'break_unlock';


    private $changedFilesCollection;


    public function getHooksArgsOrder()
    {
        return array(
            self::START_COMMIT => array(self::REPOS_PATH, self::USER, self::CAPABILITIES, self::TXN_NAME),
            self::PRE_COMMIT => array(self::REPOS_PATH, self::TXN_NAME),
            self::POST_COMMIT => array(self::REPOS_PATH, self::REVISION),
            self::PRE_REVPROP_CHANGE => array(self::REPOS_PATH, self::REVISION, self::USER, self::PROPNAME, self::ACTION),
            self::POST_REVPROP_CHANGE => array(self::REPOS_PATH, self::REVISION, self::USER, self::PROPNAME, self::ACTION),
            self::PRE_LOCK => array(self::REPOS_PATH, self::PATH, self::USER, self::COMMENT, self::STEAL),
            self::POST_LOCK => array(self::REPOS_PATH, self::USER),
            self::PRE_UNLOCK => array(self::REPOS_PATH, self::PATH, self::USER, self::TOKEN, self::BREAK_UNLOCK),
            self::POST_UNLOCK => array(self::REPOS_PATH, self::USER),
        );
    }


    private function buildTransCMDParams()
    {
        switch ($this->hook) {
            case self::PRE_REVPROP_CHANGE:
            case self::POST_REVPROP_CHANGE:
            case self::POST_COMMIT:
                return '-r ' . $this->getRevisionId();
            case self::START_COMMIT:
            case self::PRE_COMMIT:
                return '-t ' . $this->getTransactionId();
            default :
                throw new \RuntimeException('Revision/Transaction is not available during ' . $this->hook);
        }
    }

    public function getRevisionId() {
        if (!$this->hasArgument(self::REVISION)) {
            throw new \RuntimeException('Revision number is not accessible at this time');
        }
        return $this->getArgument(self::REVISION);
    }


    public function getTransactionId() {
        if (!$this->hasArgument(self::TXN_NAME)) {
            throw new \RuntimeException('Transaction name is not accessible at this time');
        }
        return $this->getArgument(self::TXN_NAME);
    }

    public function getChangedFilesCollection() {
        if (null === $this->changedFilesCollection) {
            $this->changedFilesCollection = $this->createEmptyFilesCollection();

            $elements = $this->executeCommand("svnlook changed {$this->buildTransCMDParams()} {$this->getArgument(self::REPOS_PATH)}");
            $byLine = explode("\n", $elements);

            foreach ($byLine as $changeDefinition) {
                $fileAction = mb_substr($changeDefinition, 0, 1);
                $dirAction = mb_substr($changeDefinition, 1, 1);
                $filePath = mb_substr($changeDefinition, 4);
                $this->changedFilesCollection->addFile(new File($fileAction, $dirAction, $filePath, $this));
            }
        }
        return $this->changedFilesCollection;
    }


    protected function createEmptyFilesCollection()
    {
        return new FilesCollection();
    }


    public function getFileContent($file) {
        return $this->executeCommand(
            sprintf("svnlook cat %s %s %s", $this->buildTransCMDParams(), $this->getArgument(self::REPOS_PATH), $file));
    }

    public function getAuthor() {
        return trim($this->executeCommand(
            sprintf("svnlook author %s %s", $this->buildTransCMDParams(), $this->getArgument(self::REPOS_PATH))));
    }
    public function getComment() {
        return $this->executeCommand(
            sprintf("svnlook log %s %s", $this->buildTransCMDParams(), $this->getArgument(self::REPOS_PATH)));
    }



}