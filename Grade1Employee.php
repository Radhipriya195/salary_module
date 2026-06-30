<?php
require_once "EmployeeDetails.php";
class Grade1Employee extends EmployeeDetails
{
    public function __construct(string $_name, string $_emp_Id,string  $_role, int $_lakhs_per_annum)
    {
        parent::__construct($_name, $_emp_Id, $_role, $_lakhs_per_annum);
    }

    public function getBonusPercentage()
    {
        return 0.05;
    }
}