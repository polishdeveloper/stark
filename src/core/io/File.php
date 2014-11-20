<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 14/05/14
 * Time: 10:53 PM
 */
namespace Stark\core\io;

use Stark\core\Repository;

class File {
    const DELETED = 'D';
    const ADDED = 'A';
    const MODIFIED = 'M';
    const NO_ACTION = ' ';

    private $type;
    private $path;
    /**
     * @var \Stark\core\Repository
     */
    private $repo;
    private $content;
    private $dir_action;

    /**
     * @param string $type
     * @param string $dirAction
     * @param string $path
     */
    public function __construct($type, $dirAction, $path, Repository $repo) {
        $this->type = $type;
        $this->path = $path;
        $this->dir_action = $dirAction;
        $this->repo = $repo;
    }

    public function getOperation() {
        return $this->type;
    }

    public function getPath() {
        return $this->path;
    }

    public function getExtension() {
        return pathinfo($this->path, PATHINFO_EXTENSION);
    }

    /**
     * @return string
     */
    public function getContent() {
        if ($this->content === null) {
            $this->content = $this->repo->getFileContent($this->path);
        }

        return $this->content;
    }

}