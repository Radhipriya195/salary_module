<?php

abstract class EmployeeDetails
{
	private string $emp_id;
	private string $name;
	private string $role;
	private int $annual_salary;
	/**
	 * saves employee details.
	 * @param string $_name           Employee name
	 * @param string $_emp_id         Employee id
	 * @param string $_role           Employee role
	 * @param int $_annual_salary     Employee's annual salary
	 * @return void
	 */
	public function __construct(string $_name, string $_emp_id, string $_role, int $_annual_salary)

	{
		$this->name = $_name;
		$this->emp_id = $_emp_id;
		$this->role = $_role;
		$this->annual_salary= $_annual_salary;
	}

	// Getters
	public function getEmpId()
	{
		return $this->emp_id;
	}
	public function getName()
	{
		return $this->name;
	}
	public function getRole()
	{
		return $this->role;
	}
	public function getAnnualSalary()
	{
		return $this->annual_salary;
	}

	// Setters
	public function setName(string $name)
	{
		$this->name = $name;
	}
	public function setRole(string $role)
	{
		$this->role = $role;
	}
	public function setSalary(int $annual_salary)
	{
		$this->annual_salary = $annual_salary;
	}
	 abstract function getBonusPercentage();
}
