<?php

require_once 'SalaryManager.php';

class mainfile
{
    public function startFunction()
    {
        $manager = new SalaryManager();
        $manager->run();
    }
}

$start = new mainfile();
$start->startFunction();
