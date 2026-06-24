<?php

class EmployeeDetails
{
	private string $empid;
	private string $name;
	private string $role;
	private int $lakhs_per_annum;

	// Constructor
	public function __construct(string $_name, string $_empid, string $_role, int $_lakhs_per_annum)
	{
		$this->name = $_name;
		$this->empid = $_empid;
		$this->role = $_role;
		$this->lakhs_per_annum = $_lakhs_per_annum;
	}

	// Getters
	public function getEmpId()
	{
		return $this->empid;
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
	public function getBonusPercentage(): float
	{
		return 0.0;
	}
}
