<?php
require_once "EmployeeDetails.php";
class Grade1Employee extends EmployeeDetails
{
    public function __construct(string $name, string $empId,string  $role, int $lakhsperannum)
    {
        parent::__construct($name, $empId, $role, $lakhsperannum);
    }

    public function getBonusPercentage(): float
    {
        return 0.05;
    }
}