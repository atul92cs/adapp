<?php
 use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require_once '../includes/dboperations.php';
$app= new \Slim\App(['settings'=>['displayErrorDetails'=>true]]);
$app->get('/test',function(Request $req,Response $res){
	    $result="it works.app is updated";
		$res->getBody()->write(json_encode(array($result)));
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
		  $response["message"]='Required Field(s)'.substr($error_fields,0,-2).'is missing or empty';
		  echo json_encode($response);
		  return false;
	  }
	  return true;
 }
   $app->run();
?>