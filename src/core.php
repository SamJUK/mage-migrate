<?php
require_once BASE_DIRECTORY.'/src/registry.php';

class core 
{
    const MODULE_BASE_DIR = BASE_DIRECTORY.'/src/modules/';
    const CONFIG_PATH = BASE_DIRECTORY.'/config.json';

    private $modules;

    public function runGuards()
    {
        $canRan = $this->canRun();
        if (is_string($canRan)) {
            $this->output($canRan);
            exit;
        } else if ($canRan !== true) {
            $this->output('Unexpected response from \'canRun\' Function exiting.');
            exit;
        }
    }

    public function displayIntroScreen()
    {
        $this->output("---------- MAGE MIGRATE -----------");
        $this->output("          Version: 1.0.0           ");
        $this->output("-----------------------------------");
        $this->output(PHP_EOL);
    }

    public function listAvailableModules()
    {
        $this->output("Please enter one of the numbers below");
        $modules = $this->getModules();
        foreach($modules as $k => $module) {
            $this->output("[$k] => {$module->getName()}");
        }
    }

    public function initiateUserModuleSelection()
    {
        $this->listAvailableModules();

        $selection = $this->waitForUserInput();
        $modules = $this->getModules();

        if(isset($modules[$selection])) {
            $modules[$selection]->execute();
        }
    
        return $this->initiateUserModuleSelection();
    }

    public function waitForUserInput($shouldTrim = true)
    {
        $handle = fopen ("php://stdin","r");
        $line = fgets($handle);
        fclose($handle);
        return $shouldTrim ? trim($line) : $line;
    }

    public function setupConfig()
    {
        // Parse config into registry
        registry::getInstance()->set('config', $this->parseConfig());
    }

    public function run()
    {
        $this->setupConfig();
        $this->runGuards();
        $this->displayIntroScreen();
        $this->initiateUserModuleSelection();
    }

    public function parseConfig()
    {
        $file = file_get_contents(self::CONFIG_PATH);
        $data = json_decode($file, true);
        return $data;
    }

    static public function output($message = '')
    {
        echo $message.PHP_EOL;
    }


    public function canRun()
    {
        if (!$this->isEnviromentCli()) {
            return 'This script needs to be run from CLI';
        }

        return true;
    }

    public function isEnviromentCli()
    {
        return php_sapi_name() === 'cli';
    }

    public function getModules()
    {
        if (is_array($this->modules) && count($this->modules)) {
            return $this->modules;
        }

        $this->modules = [];
        $mods = array_values(array_diff(scandir($this->getModuleBaseDirectory()), array('.', '..')));
        foreach($mods as $mod) {
            require_once $this->getModuleBaseDirectory().$mod;
            $class = str_replace('.php', '', $mod);
            $this->modules[] = new $class;
        }

        return $this->modules;
    }

    public function getModuleBaseDirectory()
    {
        return self::MODULE_BASE_DIR;
    }
}