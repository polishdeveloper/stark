<?php
namespace Stark\core\repository;


abstract class CommandlineRepo
{
    private $hooksArgsOrder = array();
    private $arguments = array();

    abstract protected function getHooksArgsOrder();

    public function __construct(/* $hook, $arg1, $arg2, $arg3 */) {
        $this->hooksArgsOrder = $this->getHooksArgsOrder();
        $arguments = func_get_args();
        $this->hook = array_shift($arguments);

        $this->parseArguments($this->hook, $arguments);
    }


    private function parseArguments($hook, $arguments) {
        if (!array_key_exists($hook, $this->hooksArgsOrder)) {
            throw new \InvalidArgumentException('Unknown hook `' . $hook . '`.' .
                'Available hooks :' . implode(',', array_keys($this->hooksArgsOrder)));
        }
        $argsCount = count($arguments);
        $expectedArgsCount = count($this->hooksArgsOrder[$hook]);

        if ($argsCount != $expectedArgsCount) {
            throw new \InvalidArgumentException("Wrong parameters count, expected $expectedArgsCount, got $argsCount. " .
                "Expected args order " . implode(' ', $this->hooksArgsOrder[$hook]));
        }

        foreach($arguments as $id => $arg) {
            $this->arguments[$this->hooksArgsOrder[$hook][$id]] = $arg;
        }
    }

    /**
     * @param string $argument
     */
    protected function getArgument($argument) {
        return $this->arguments[$argument];
    }

    /**
     * @param string $argument
     */
    protected function hasArgument($argument) {
        return array_key_exists($argument, $this->arguments);
    }


}