<?php
namespace Stark\tasks\PHP;

use Stark\core\io\File;
use Stark\core\tasks\Task;
use Stark\tasks\AbstractPHPFilesTask;

class PHPCs extends AbstractPHPFilesTask{

    const PHPCS_NOT_INTERACTIVE_MODE = false;

    private $phpcs_verbosity = 0;
    private $phpcs_tab_width = 4;
    private $phpcs_encoding  = 'UTF-8';

    private $standard = 'PSR-2';

    public function setStandard($standard)
    {
        $this->standard = $standard;
    }

    public function getName() {
        return 'PHP Code Style check';
    }

    protected function executeOnPHPFile(File $file)
    {
        $code_sniffer = new \PHP_CodeSniffer($this->phpcs_verbosity, $this->phpcs_tab_width, $this->phpcs_encoding, self::PHPCS_NOT_INTERACTIVE_MODE);
        //Load the standard
        $code_sniffer->process(array(), $this->standard, array());
        $file_result = $code_sniffer->processFile($file->getPath(), $file->getContent());

        if ($file_result->getErrorCount()) {
            $this->handlePHPCSErrors($file, $file_result);
        }
    }

    private function handlePHPCSErrors(File $file, \PHP_CodeSniffer_File $phpcsFile)
    {
        $maxLine = strlen(max(array_keys($phpcsFile->getErrors())));
        $byLine = $this->prepareFileContent($file);
        foreach ($phpcsFile->getErrors() as $lineNumber => $errorsByLine) {
            $message = sprintf("   | Line %{$maxLine}s:%s\n", $lineNumber, $byLine[$lineNumber+1]);
            foreach($errorsByLine as $column => $errorsCollection) {
                foreach ($errorsCollection as $error) {
                    $message .= sprintf("   |%{$maxLine}s| Error at column %s: %s\n", '', $column, $error['message']);
                }
            }
            $this->pushError($message);
        }
    }

    private function prepareFileContent(File $file)
    {
        $byLine = array();
        $lineNo = 1;
        foreach(preg_split('/[(\r\n)|(\n)|(\n\r)]/', $file->getContent()) as $line){
            $byLine[$lineNo++] = $line;
        }
        return $byLine;
    }

}