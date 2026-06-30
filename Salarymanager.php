<?php
require_once 'EmployeeDetails.php';
require_once 'Grade1Employee.php';
require_once 'Grade2Employee.php';
require_once 'Grade3Employee.php';
require_once 'Database.php';

class SalaryManager
{
	private $employees = [];
	private mysqli $connection;

	public function __construct()
	{
		$database = new Database();
		$this->connection = $database->connect();
		$result = $this->connection->query("SELECT * FROM employees");

		if (!$result) {
			die("Query failed: " . $this->connection->error);
		}
		while ($emp = $result->fetch_assoc()) {
			$this->employees[$emp["employee_id"]] = $this->createEmployee($emp);
		}
	}
	private function createEmployee(array $emp)
	{
		$grade = $emp["grade"];

		if ($grade == 1) {
			return new Grade1Employee($emp["name"], $emp["employee_id"], $emp["role"], $emp["lakhsperannum"]);
		} elseif ($grade == 2) {
			return new Grade2Employee($emp["name"], $emp["employee_id"], $emp["role"], $emp["lakhsperannum"]);
		} elseif ($grade == 3) {
			return new Grade3Employee($emp["name"], $emp["employee_id"], $emp["role"], $emp["lakhsperannum"]);
		}
		throw new Exception("Invalid grade: " . $grade);
	}

	/**
	 * Finds an employee by their ID
	 * @param $_employee_id  Employee id
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
			$yearly_tax = $lakhs_per_annum * 0.05;
		} else {
			$yearly_tax = $lakhs_per_annum * 0.08;
		}

		$monthly_tax = $yearly_tax / 12;
		$bonus = $earned_salary * $_employee->getBonusPercentage();
		$in_hand_salary = $earned_salary + $bonus - $pf - $monthly_tax;

		echo "\n-------SALARY CALCULATION-------\n";
		echo "Monthly Salary: ₹" . round($monthly_salary) . "\n";
		echo "PF Deduction (12%): ₹" . round($pf) . "\n";
		echo "Tax Deduction: ₹" . round($monthly_tax) . "\n";
		echo "bonus:" . round($bonus) . "\n";
		echo "Final In-hand: ₹" . round($in_hand_salary) . "\n";

		$monthly_payslip = [
			"emp_id" => $_employee->getEmpId(),
			"name" => $_employee->getName(),
			"role" => $_employee->getRole(),
			"month" => date("F Y"),
			"total_days" => date("t"),
			"days_worked" => $_days_worked,
			"monthly_salary" => round($monthly_salary),
			"pf" => round($pf),
			"tax" => round($monthly_tax),
			"final_salary" => round($in_hand_salary)
		];
		$this->saveToDataBase($monthly_payslip);
	}
	/**
	 * Saves data to json
	 * @param $_monthly_payslip provides monthly salary details
	 * @return void
	 */
	public function saveToDataBase(array $_monthly_payslip)
	{
		$statement = $this->connection->prepare(
			"SELECT id FROM salary_transactions WHERE emp_id = ? AND month = ?"
		);
		$statement->bind_param("ss", $_monthly_payslip["emp_id"], $_monthly_payslip["month"]);
		$statement->execute();
		$result = $statement->get_result();

		if ($result->num_rows > 0) {
			echo "\n Salary already generated for this month!\n";
			return;
		}

		// Insert
		$statement = $this->connection->prepare(
			"INSERT INTO salary_transactions 
            (emp_id, name, role, month, total_days, days_worked, monthly_salary, pf, tax, final_salary)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
		);

		$statement->bind_param(
			"ssssiiiiii",
			$_monthly_payslip["emp_id"],
			$_monthly_payslip["name"],
			$_monthly_payslip["role"],
			$_monthly_payslip["month"],
			$_monthly_payslip["total_days"],
			$_monthly_payslip["days_worked"],
			$_monthly_payslip["monthly_salary"],
			$_monthly_payslip["pf"],
			$_monthly_payslip["tax"],
			$_monthly_payslip["final_salary"]
		);
		if ($statement->execute()) {
			echo "\nSalary saved to database successfully!\n";
		} else {
			echo "\nError saving salary: " . $statement->error . "\n";
		}
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
