<?php
namespace Stark\core\repository;

use Stark\core\Repository;

/**
 * Class GIT
 * @see https://github.com/git/git/blob/master/Documentation/githooks.txt
 * @package Stark\core\repository
 */
class GIT extends CommandlineRepo implements Repository{

    //HOOKS
    const APPLYPATCH_MSG     = 'applypatch-msg';
    const PRE_APPLYPATCH     = 'pre-applypatch';
    const POST_APPLYPATCH    = 'post-applypatch';
    const PRE_COMMIT         = 'pre-commit';
    const PREPARE_COMMIT_MSG = 'prepare-commit-msg';
    const COMMIT_MSG         = 'commit-msg';
    const POST_COMMIT        = 'post-commit';
    const PRE_REBASE         = 'pre-rebase';
    const POST_MERGE         = 'post-merge';
    // <local ref> SP <local sha1> SP <remote ref> SP <remote sha1> LF
    const PRE_PUSH           = 'pre-push';
    // <old-value> SP <new-value> SP <ref-name> LF
    const PRE_RECEIVE        = 'pre-receive';
    const UPDATE             = 'update';
    // <old-value> SP <new-value> SP <ref-name> LF
    const POST_RECEIVE       = 'post-receive';
    const POST_UPDATE        = 'post-update';
    const PRE_AUTO_GC        = 'pre-auto-gc';
    // <old-sha1> SP <new-sha1> [ SP <extra-info> ] LF
    const POST_REWRITE       = 'post-rewrite';
    const POST_PUSH          = 'post-push';

    //ARGUMENTS
    const TMP_LOG_FILE_NAME = 'tmp-log-filename';
    const MESSAGE_SOURCE    = 'message_source';
    const UPSTREAM          = 'upstream';
    const BRANCH            = 'branch';
    const STATUS_FLAG       = 'status-flag';
    const NAME              = 'name';
    const LOCATION          = 'location';
    const OLD_OBJ_NAME      = 'old-obj-name';
    const NEW_OBJ_NAME      = 'new-obj-name';
    const COMMAND           = 'command';

    const REF_NOT_EXISTS = '0000000000000000000000000000000000000000';
    const VARIABLE_ARGS  = 'VAR';

    protected function getHooksArgsOrder() {
        return array(
            self::APPLYPATCH_MSG     => array(self::TMP_LOG_FILE_NAME),
            self::PRE_APPLYPATCH     => array(),
            self::POST_APPLYPATCH    => array(),
            self::PRE_COMMIT         => array(),
            self::PREPARE_COMMIT_MSG => array(self::TMP_LOG_FILE_NAME, self::MESSAGE_SOURCE),
            self::COMMIT_MSG         => array(self::TMP_LOG_FILE_NAME),
            self::POST_COMMIT        => array(),
            self::PRE_REBASE         => array(self::UPSTREAM, self::BRANCH),
            self::POST_MERGE         => array(self::STATUS_FLAG),
            self::PRE_PUSH           => array(self::NAME, self::LOCATION),
            self::PRE_RECEIVE        => array(),
            self::UPDATE             => array(self::NAME, self::OLD_OBJ_NAME, self::NEW_OBJ_NAME),
            self::POST_RECEIVE       => array(),
            self::POST_UPDATE        => self::VARIABLE_ARGS,
            self::PRE_AUTO_GC        => array(),
            self::POST_REWRITE       => array(self::COMMAND),
            self::POST_PUSH          => array(),
        );
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

    public function getRevisionId() {

    }

}