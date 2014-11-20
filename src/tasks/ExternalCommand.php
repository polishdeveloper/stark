<?php
namespace Stark\tasks;

use Stark\core\tasks\Task;

class ExternalCommand extends Task{

    private $command;
    private $successExitCode = 0;
    private $errorMessage = "Execution of remote command '%s' failed with code '%s'";
    private $includeOutput = false;

    public function getName() {
        return 'External command execution';
    }

    public function setCommand($command) {
        $this->command = $command;
    }
    public function setSuccessExitCode($code) {
        $this->successExitCode = $code;
    }
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
    }
    public function setIncludeOutput($includeOutput) {
        $this->includeOutput = $this->paramIsTrue($includeOutput);
    }

    public function execute() {
        $command = $this->expandVariable($this->command);

        exec($command, $output, $return_var);
        if ($return_var != $this->successExitCode) {
            $this->pushError(sprintf($this->errorMessage, $this->command, $return_var));
            if ($this->includeOutput && $output) {
                foreach ($output as $outputLine) {
                    $this->pushError($outputLine);
                }
            }
        }


    }
}