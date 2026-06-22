<?php
require_once 'Employeedetails.php';

class SalaryManager
{
    private $file = "employees.json";
    private $employees = [];

    public function __construct()
    {
        $data = json_decode(file_get_contents($this->file));

        foreach ($data as $emp) {
            $this->employees[] = new Employeedetails($emp->name, $emp->empid, $emp->role, $emp->salary);
        }
    }
    /**
     * Finds an employee by their ID
     * 
     * @param int $_id
     */
    public function findEmployee($_id)
    {
        foreach ($this->employees as $emp) {
            if ($emp->getEmpId() == $_id) {
                return $emp;
            }
        }
        return null;
    }
    /**
     * Calculates montly salary
     * 
     * @param Employeedetails $_employee Employee Object,
     * @param int $_daysWorked No of days worked
     * @return void
     */
    public function calculateSalary(Employeedetails $_employee, int $_daysWorked, int $_totalDays)
    {
        $message = "";

        $perDay = $_employee->getSalary() / $_totalDays;
        if ($_daysWorked === $_totalDays) {
            $final = ($perDay * $_daysWorked) + 1000;
            $message = "Bonus Added 1000rs";
        } else if ($_daysWorked < $_totalDays / 2) {
            $final = ($perDay * $_daysWorked) - 1000;
            $message = "Deduction charged 1000rs";
        } else {
            $final = ($perDay * $_daysWorked);
            $message = "No bonus or Deduction charged";
        }

        echo "\n-------CALCULATED SALARY-------\n";
        echo "Per Day Salary: ₹" . round($perDay) . "\n";
        echo "Bonus or Deduction : " . $message . "\n";
        echo "Final Salary: ₹" . round($final) . "\n";

        $record = [
            "Name" => $_employee->getName(),
            "Emp_id" => $_employee->getEmpId(),
            "Role" => $_employee->getRole(),
            "Month" => date("F"),
            "Total_days_this_month" => date("t"),
            "Days_you_worked" => $_daysWorked,
            "Per_day_salary" => round($perDay),
            "Final_salary" => round($final)
        ];

        $this->saveToJson($record);
    }

    /**
     * Saves data to json
     * @param array $record updated details of the employee
     * @return void
     */

    public function saveToJson(array $record)
    {
        $data = [];

        if (file_exists("salary.json")) {
            $data = json_decode(file_get_contents("salary.json"), true);
        }

        $data[] = $record;

        file_put_contents("salary.json", json_encode($data, JSON_PRETTY_PRINT));
    }

    /**
     * Executes employees final salary
     * return void
     */
    public function run()
    {
        echo "-------EMPLOYEE DETAILS-------\n";

        while (true) {
            $id = readline("Enter Employee ID: ");
            $employee = $this->findEmployee($id);

            if ($employee !== null) {
                break;
            }
            echo "Employee not found, Enter a valid ID.\n\n";
        }
        echo "\nName: " . $employee->getName() . "\n";
        echo "Salary: ₹" . $employee->getSalary() . "\n";
        echo "Role: " . $employee->getRole() . "\n \n";

        $totalDays = date("t");
        echo "-------MONTH INFO------\n";
        echo "Current month is: " . date("F") . "(" . $totalDays . " Days) \n";

        $_daysWorked = readline("Enter days you worked: ");

        if (!is_numeric($_daysWorked) || $_daysWorked <= 0 || $_daysWorked > $totalDays) {
            echo "Invalid days entered : Days worked cannot exceed total days. \n \n";
            return;
        }

        $this->calculateSalary($employee, $_daysWorked, $totalDays);
    }
}
