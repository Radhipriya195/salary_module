<?php

require_once 'SalaryManager.php';

class MainFile
{
	public function startFunction()
	{
		$manager = new SalaryManager();
		$manager->run();
	}
}

$start = new MainFile();
$start->startFunction();
