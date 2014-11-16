<?php
/**
 * Created by PhpStorm.
 * User: raynor
 * Date: 05/05/14
 * Time: 12:16 AM
 */
namespace Stark\core\io;

use Stark\core\Container;
use Stark\core\ContainerAwareTrait;

class Renderer {
    use ContainerAwareTrait;

    private $messages;


    public function setErrors(array $errors)
    {
        $this->messages = $errors;
    }

    public function render()
    {
        $output = $this->getContainer()->getOutput();

        if (!empty($this->messages)) {
            $output->writeLn("Cannot perform action. Errors:");

            $i = 1;
            foreach ($this->messages as $taskName => $errors) {
                $this->renderTaskMessages($output, $i, $taskName, $errors);
                $i++;
            }
        }
    }

    /**
     * @param $output
     * @param $i
     * @param $taskName
     * @param $errors
     */
    public function renderTaskMessages(Output $output, $index, $taskName, $errors)
    {
        $output->setPrefix('');
        $output->writeLn(sprintf("%s. Hook %s failed with errors", $index, $taskName));
        $output->setPrefix('    ');
        foreach ($errors as $error) {
            $output->writeLn($error);
        }
        $output->setPrefix('');
    }


}