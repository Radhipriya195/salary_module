<?php
class Employeedetails
{
	private $name;
	private $empid;
	private $role;
	private $salary;

	public function __construct($name, $empid,$role,$salary)
	{
		$this->name = $name;
		$this->empid = $empid;
		$this->salary = $salary;
		$this->role = $role;
	}

	public function getName() { return $this->name; }
	public function getEmpId() { return $this->empid; }
	public function getSalary() { return $this->salary; }
	public function getRole() { return $this->role;}

	public function toArray()
	{
		return [
			"Name" => $this->name,
			"Empid" => $this->empid,
			"Salary" => $this->salary,
			"Role" => $this->role
		];
	}
}