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
    /**
     * @return null|string
     */
    public function getComment();

    /**
     * @return null|string
     */
    public function getAuthor();

    /**
     * @return null|string
     */
    public function getFileContent($filePath);
    public function getChangedFilesCollection();


}