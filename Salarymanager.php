<?php
require_once 'EmployeeDetails.php';
require_once 'Grade1Employee.php';
require_once 'Grade2Employee.php';
require_once 'Grade3Employee.php';

class SalaryManager
{
	private $file = "employees.json";
	private $employees = [];

	public function __construct()
	{ 
		$data = json_decode(file_get_contents($this->file), True);

		foreach ($data as $emp) {
			$this->employees[$emp["empId"]] = $this->createEmployee($emp);
		}
	}
	private function createEmployee($emp)
	{
		$grade = $emp["grade"];

		if ($grade == 1) {
			return new Grade1Employee($emp["name"], $emp["empId"], $emp["role"], $emp["lakhsperannum"]);
		} elseif ($grade == 2) {
			return new Grade2Employee($emp["name"], $emp["empId"], $emp["role"], $emp["lakhsperannum"]);
		} elseif ($grade == 3) {
			return new Grade3Employee($emp["name"], $emp["empId"], $emp["role"], $emp["lakhsperannum"]);
		} else {
			return new EmployeeDetails($emp["name"], $emp["empId"], $emp["role"], $emp["lakhsperannum"]);
		}
	}

	/**
	 * Finds an employee by their ID
	 * @param $_emp_id  Employee id
	 * @return EmployeeDetails|null
	 */
	public function findEmployee(string $_employee_id)
	{
		return $this->employees[$_employee_id] ?? null;
	}

	/**
	 * Calculates monthly salary
	 * @param $_employee   Employee details
	 * @param $_days_worked  Days worked by the employee
	 * @param $_total_days  Total days in the month
	 * @return void
	 */
	public function calculateSalary(EmployeeDetails $_employee, int $_days_worked, int $_total_days)
	{
		$monthly_salary = $_employee->getLakhsPerAnnum() / 12;
		$per_day_salary = $monthly_salary / $_total_days;
		$earned_salary = $per_day_salary * $_days_worked;

		$pf = $monthly_salary * 0.12;
		$lakhs_per_annum = $_employee->getLakhsPerAnnum();

		if ($lakhs_per_annum <= 300000) {
			$yearly_tax = 0;
		} elseif ($lakhs_per_annum <= 600000) {
			$yearly_tax = (int)$lakhs_per_annum * 0.05;
		} else {
			$yearly_tax = (int) $lakhs_per_annum * 0.08;
		}

		$monthly_tax = $yearly_tax / 12;
		$bonus = $earned_salary * $_employee->getBonusPercentage();
		$in_hand_salary = $earned_salary + $bonus - $pf - $monthly_tax;

		echo "\n-------SALARY CALCULATION-------\n";
		echo "Monthly Salary: ₹" . round($monthly_salary) . "\n";
		echo "PF Deduction (12%): ₹" . round($pf) . "\n";
		echo "Tax Deduction: ₹" . round($monthly_tax) . "\n";
		echo "bonus:" .round($bonus) . "\n";
		echo "Final In-hand: ₹" . round($in_hand_salary) . "\n";

		$monthly_payslip = [
			"Name" => $_employee->getName(),
			"Emp_id" => $_employee->getEmpId(),
			"Role" => $_employee->getRole(),
			"Month" => date("F"),
			"Total_days_this_month" => date("t"),
			"Days_you_worked" => $_days_worked,
			"Monthly_salary" => round($monthly_salary),
			"PF" => round($pf),
			"Tax" => round($monthly_tax),
			"Final_salary" => round($in_hand_salary)

		];

		$this->saveToJson($monthly_payslip);
	}
	/**
	 * Saves data to json
	 * @param $_monthly_payslip provides monthly salary details
	 * @return void
	 */
	public function saveToJson(array $_monthly_payslip)
	{
		$data = [];

		if (file_exists("salary.json")) {
			$data = json_decode(file_get_contents("salary.json"), true);
		}

		$data[] = $_monthly_payslip;

		file_put_contents("salary.json", json_encode($data, JSON_PRETTY_PRINT));
	}

	/**
	 * Executes employee salary flow
	 * @return void
	 */
	public function run()
	{
		echo "-------EMPLOYEE DETAILS-------\n";

		while (true) {
			$employee_id = readline("Enter Employee ID: ");
			$employee = $this->findEmployee($employee_id);

			if ($employee !== null) {
				break;
			}
			echo "Employee not found, Enter a valid ID.\n\n";
		}

		echo "\nName: " . $employee->getName() . "\n";
		echo "LPA: ₹" . $employee->getLakhsPerAnnum() . "\n";
		echo "Role: " . $employee->getRole() . "\n \n";

		$total_days = date("t");

		echo "-------MONTH INFO------\n";
		echo "Current month is: " . date("F") . " (" . $total_days . " Days)\n";

		$days_worked = readline("Enter days you worked: ");

		if (!is_numeric($days_worked) || $days_worked <= 0 || $days_worked > $total_days) {
			echo "Invalid days entered : Days worked cannot exceed total days.\n\n";
			return;
		}

		$this->calculateSalary($employee, $days_worked, $total_days);
	}
}
