<?php
require_once 'EmployeeDetails.php';

class SalaryManager
{
	private $file = "employees.json";
	private $employees = [];

	public function __construct()
	{
		$data = json_decode(file_get_contents($this->file), True);

		foreach ($data as $emp) {
			$this->employees[$emp["empId"]] = new EmployeeDetails($emp["name"], $emp["empId"], $emp["role"], $emp["LPA"]);
		}
	}

	/**
	 * Finds an employee by their ID
	 * @param $_emp_id - Employee id
	 * @return EmployeeDetails|null
	 */
	public function findEmployee(string $_emp_id)
	{
		return $this->employees[$_emp_id] ?? null;
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
		$monthly_salary = $_employee->getLPA() / 12;
		$per_day_salary = $monthly_salary / $_total_days;
		$earned_salary = $per_day_salary * $_days_worked;

		$pf = $monthly_salary * 0.12;
		$lpa = $_employee->getLPA();

		if ($lpa <= 300000) {
			$yearly_tax = 0;
		} elseif ($lpa <= 600000) {
			$yearly_tax = (int)$lpa * 0.05;
		} else {
			$yearly_tax = (int) $lpa * 0.08;
		}

		$monthly_tax = $yearly_tax / 12;
		$in_hand_salary = $earned_salary - $pf - $monthly_tax;

		echo "\n-------SALARY CALCULATION-------\n";
		echo "Monthly Salary: ₹" . round($monthly_salary) . "\n";
		echo "PF Deduction (12%): ₹" . round($pf) . "\n";
		echo "Tax Deduction: ₹" . round($monthly_tax) . "\n";
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
	 * @param $_record provides monthly salary details
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
		echo "LPA: ₹" . $employee->getLPA() . "\n";
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
