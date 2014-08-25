<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 04/05/14
 * Time: 2:49 PM
 */
namespace Stark\core\io;

class HooksXMLReader {

    private $xml;


    public function __construct($filename) {
        if (!file_exists($filename)) {
            throw new \InvalidArgumentException("File [$filename] doesn't exist");
        }
        $this->xml = @simplexml_load_file($filename);
        if (!$this->xml) {
            throw new \InvalidArgumentException("Hooks file [$filename] is in invalid format");
        }
    }


    public function parse() {
        $tasks = $this->parseTree($this->xml);
        unset($tasks['hooks']);
    }



    public function getHooks($hook) {
        return $this->parseTree($this->xml->hooks->$hook);
    }



    private function parseTree(\SimpleXMLElement $tree) {
        /**
         * @var $taskDefinition \SimpleXMLElement
         */
        $tasks = array();
        if (!empty($tree)) {
            foreach ($tree->children() as $name => $taskDefinition) {
                $attrs = (array)$taskDefinition->attributes();
                $tasks[] = array(
                    'name' => (string)$name,
                    'params' => array_key_exists('@attributes', $attrs) ? $attrs['@attributes'] : array()
                );
            }
        }
        return $tasks;

    }



}
