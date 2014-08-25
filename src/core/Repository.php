<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 05/05/14
 * Time: 12:04 AM
 */
namespace Stark\core;

interface Repository
{
    public function getComment();
    public function getAuthor();
    public function getFileContent($filePath);

    public function getChangedFilesCollection();

}