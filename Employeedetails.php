<?php

abstract class EmployeeDetails
{
	private string $emp_id;
	private string $name;
	private string $role;
	private int $lakhs_per_annum;
	/**
	 * saves employee details.
	 * @param string $_name           Employee name
	 * @param string $_emp_id         Employee id
	 * @param string $_role           Employee role
	 * @param int $_lakhs_per_annum   Employee's LPA 
	 * @return void
	 */
	public function __construct(string $_name, string $_emp_id, string $_role, int $_lakhs_per_annum)
	{
		$this->name = $_name;
		$this->emp_id = $_emp_id;
		$this->role = $_role;
		$this->lakhs_per_annum = $_lakhs_per_annum;
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
	public function getLakhsPerAnnum()
	{
		return $this->lakhs_per_annum;
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
	public function setSalary(int $lakhs_per_annum)
	{
		$this->lakhs_per_annum = $lakhs_per_annum;
	}
	 abstract function getBonusPercentage();
}
