<?php
class DbOperation
{
  private $con ;
  function __construct()
  {
    require_once dirname(__FILE__).'/dbconnect.php';
	$db=new DbConnect();
	$this->con=$db->connect();
  }
  function registerUser($name,$password,$dob,$gender,$referal,$phone)
	{
		if(!$this->isUserExist($phone))
		{
		$pass=md5($password);
		$stmt=$this->con->prepare("INSERT INTO users (name,password,dob,gender,referalcode,phone) VALUES (?,?,?,?,?,?)");
		$stmt->bind_param("ssssss",$name,$pass,$dob,$gender,$referal,$phone);
		if($stmt->execute())
			return USER_CREATED;
		
		return USER_CREATION_FAILED;
		}
		return USER_EXIST;
	}
	
	function userLogin($phone,$password)
	{
		$pass=md5($password);
		$stmt=$this->con->prepare("SELECT * FROM users WHERE phone=? AND password=?");
		$stmt->bind_param("ss",$phone,$pass);
		$stmt->execute();
		$stmt->store_result();
		return $stmt->num_rows>0;
		
	}
  function userUpdate($id,$name,$phone,$password)
	{
		$pass=md5($password);
		$stmt=$this->con->prepare("UPDATE users SET name=?phone=?,password=? WHERE id=?");
		$stmt->bind_param("sssss",$name,$phone,$pass,$id);
		if($stmt->execute())
			return true;
		return false;
	}
	function isUserExist($phone)
	{
		$stmt=$this->con->prepare("SELECT * FROM users WHERE phone=?");
		$stmt->bind_param("s",$phone);
		$stmt->execute();
		$stmt->store_result();
		return $stmt->num_rows>0;
	}
	function getUserByPhone($phone)
	{
		$stmt=$this->con->prepare("SELECT id,name,phone FROM users WHERE phone=?");
		$stmt->bind_param("s",$phone);
		$stmt->execute();
		$stmt->bind_result($id,$name,$phone);
		$user=array();
		$stmt->fetch();
		$user['Id']=$id;
		$user['Name']=$name;
		$user['Phone']=$phone;
		$user['Email']=$email;
		return $user;
		
	}
  }?>