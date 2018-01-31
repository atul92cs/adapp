<?php
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require_once '../include/dboperations.php';
$app= new \Slim\App(['settings'=>['displayErrorDetails'=>true]]);
$app->get('/test',function(Request $req,Response $res){
	    $result="it works.app is updated";
		$res->getBody()->write(json_encode(array($result)));
});
$app->post('/register',function(Request $req,Response $res)
{
	if(isTheseParametersAvailable(array('name','password','dob','gender','referalcode','phone')))
	{
		$requestedData=$req->getParsedBody();
		$name=$requestedData['name'];
		$password=$requestedData['password'];
		$dob=$requestedData['dob'];
		$gender=$requestedData['gender'];
		$referal=$requestedData['referalcode'];
		$phone=$requestedData['phone'];
		$db=new DbOperation();
		$responseData=array();
		
		$result=$db->registerUser($name,$password,$dob,$gender,$referal,$phone);
		
		if($result==USER_CREATED)
		{
			$responseData['error']=false;
			$responseData['Message']='User Registered Succesfully';
			$reponseData['User']=$db->getUserByPhone($phone);
		}
		else if($result==USER_CREATION_FAILED)
		{
			$responseData['error']=true;
			$responseData['Message']='Error: User Creation failed, please try again';
			
		}
		else if($result==USER_EXISTS)
		{
			$responseData['error']=true;
			$responseData['Message']='Error:user already exists';
		}
		$res->getBody()->write(json_encode($responseData));
	}
	
});
$app->post('/login',function(Request $req,Response $res)
{
	if(isTheseParametersAvailable(array('phone','password')))
	{
		$requestedData=$req->getParsedBody();
		$phone=$requestedData['phone'];
		$password=$requestedData['password'];
		 $db=new DbOperation();
		 $responseData=array();
		
		 
		 $result=$db->userLogin($phone,$password);
		 if($result==true)
		 {
			 $responseData['error']=false;
			 $responseData['user']='user logged in';
		 }
		 else
		 {
			 $responseData['error']=true;
			 $responseData['Message']='Error:Please try again';
		 }
	      $res->getBody()->write(json_encode($responseData));	
	}
});
function isTheseParametersAvailable($required_fields)
 {
	  $error=false;
	  $error_fields="";
	  $request_params=$_REQUEST;
	  foreach($required_fields as $field)
	  {
		  if(!isset($request_params[$field])||strlen(trim($request_params[$field]))<=0)
		  {
			  $error=true;
			  $error_fields=$field.',';
			  
		  }
	  }
	  if($error)
	  {
		  $response=array();
		  $response["error"]=true;
		  $response["message"]='Required Field(s)'.substr($error_fields,0,-1).'is missing or empty';
		  echo json_encode($response);
		  return false;
	  }
	  return true;
 }
   $app->run();
?>