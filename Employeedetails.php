<?php

class EmployeeDetails
{
	private string $empid;
	private string $name;
	private string $role;
	private int $LPA;

	// Constructor
	public function __construct(string $_name, string $_empid, string $_role, int $_LPA)
	{
		$this->name = $_name;
		$this->empid = $_empid;
		$this->role = $_role;
		$this->LPA = $_LPA;
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
	public function getLPA()
	{
		return $this->LPA;
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
	public function setSalary(int $LPA)
	{
		$this->LPA = $LPA;
	}

	// Convert back to JSON format 
	public function toArray()
	{
		return [
			"name" => $this->name,
			"role" => $this->role,
			"LPA" => $this->LPA
		];
	}
}
