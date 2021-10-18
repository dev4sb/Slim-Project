<?php



ini_set('memory_limit', '256M');

require 'vendor/autoload.php';
require 'config.php';
require 'Database.php';

date_default_timezone_set('Asia/Kolkata');

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\UploadedFile;

function connectdb()
{
	$db = new Database('jquery', 'root', '', 'localhost');
	return  $db;
}

$app = new \Slim\App();

$container = $app->getContainer();
$container['upload_directory'] = __DIR__ . '/uploads';

/*

$app->get('/ajsiasdiajsdiasdiasj',function(Request $request,Response $response){

$allGetVars = $request->getQueryParams();
return $response->withJson($result);

});


$app->post('/NAME',function(Request $request,Response $response){

$allPostPutVars = $request->getParsedBody();
return $response->withJson($result);

});



insert 

$insertdata=[
		'url'=>$url,
		'status'=>$httpcode,
		'lastcheck'=>$current_date
	];


$db=connectdb();
$db->insert('statuscheck',$insertdata);



updaTE

$updatedata=[
		'no'=>$newid,
		'url'=>$xy,
		'status'=>$httpcode,
		'lastcheck'=>$current_date
		];


		$db=connectdb();
		
		
		$db->update('statuscheck',$updatedata,array('no'=>$newid));


delete
		$db=connectdb();
		$db->delete('statuscheck',array('no'=>$newid));


		//new $db->delete('studregister',array("sid"=>$delid));



		select

		$db = connectdb();
   $db->select("studregister",array("sid"=>$editid));
   $editvalue =$db->result_array();







		custom query
		$db->query("select url from statuscheck where no='$newid'");
		$data=$db->result();






*/
function moveUploadedFile($directory, UploadedFile $uploadedFile)
{
	$extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
	$basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
	$filename = sprintf('%s.%0.8s', $basename, $extension);

	$uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

	return $filename;
}

function remove_file($id)
{
	$db = connectdb();
	$data = $db->select("task3_crud", array("id" => "$id"), false, false, "AND", "image");
	$result = $db->result_array();
	if ($result['0']['image'] != '') {
		$file_name = "Images/" . $result['0']['image'];
		if (file_exists($file_name)) {
			unlink($file_name);
			return true;
		} else {
			return false;
		}
	}
}

function tp_api_call($url)
{
	$curl_sess=curl_init();
	$URL=$url;
	curl_setopt($curl_sess,CURLOPT_URL,$URL);
	$curl_data=curl_exec($curl_sess);
	curl_close($curl_sess);
	return $curl_data;
}

function no_of_page($limit)
{
	$db = connectdb();
	$data = $db->select("task3_crud");
	$result = $db->result_array();
	$total_pages = ceil(count($result) / $limit);
	return $total_pages;
}
//LOGIN API
$app->post('/login', function (Request $request, Response $response) {
	$values = $request->getParsedBody();
	$email = $values['email'];
	$password = $values['password'];
	$db = connectdb();
	$data = $db->select("task3_crud", array("email" => "$email", "password" => "$password"), false, false, "AND", "id");

	$result = $db->result_array();
	$msg = array("message" => "Successfully Login", "status" => "true");
	if (count($result) > 0) {
		session_start();
		$_SESSION["user_id"] = $result[0]['id'];
		$msg = array("message" => "Successfully Login", "status" => "true");
		return $response->withJson($msg);
	} else {
		$msg = array("message" => "User Not Exist", "status" => "false");
		return $response->withJson($msg);
	}
});

//LOGOUT API
$app->post('/logout', function (Request $request, Response $response) {
	session_start();
	$_SESSION["user_id"] = "";
	if (session_destroy()) {
		$msg = array("message" => "Log Out Successfully", "status" => "true");
		return $response->withJson($msg);
	} else {
		$msg = array("message" => "Can't Log Out", "status" => "false");
		return $response->withJson($msg);
	}
});

//LOAD ALL DATA API
$app->post('/load-data', function (Request $request, Response $response) {
	$values = $request->getParsedBody();
	$page_no = $values['pg'];

	$limit_per_page = 5;
	$pages = no_of_page($limit_per_page);

	$offset = ($page_no - 1) * $limit_per_page;
	$db = connectdb();
	$data = $db->query("select * from task3_crud limit $offset,$limit_per_page ");

	$result = $db->result_array();
	if (count($result) > 0) {

		$msg = array("Message" => array("message" => "No Record Found", "status" => "true", "pages" => "$pages"));
		return $response->withJson(array_merge($msg, $result));
	} else {
		$msg = array("message" => "No Record Found", "status" => "false");
		return $response->withJson($msg);
	}
});


//LOAD SINGLE DATA API
$app->post('/load-single-data', function (Request $request, Response $response) {

	$values = $request->getParsedBody();
	$id = $values['id'];

	$db = connectdb();
	$data = $db->select("task3_crud", array("id" => "$id"));

	$result = $db->result_array();


	$msg = array("message" => "Successfully Login", "status" => "true");

	return $response->withJson($result);
});


//INSERT DATA API
$app->post('/insert', function (Request $request, Response $response) {

	$values = $request->getParsedBody();
	$files = $request->getUploadedFiles();
	$myFile = $files['file'];


	if ($myFile->getError() === UPLOAD_ERR_OK) {
		$uploadFileName = $myFile->getClientFilename();
		$filename = moveUploadedFile('Images/', $files['file']);
		// move_uploaded_file();


		$insertdata = [
			'name' => $values['name'],
			'dob' => $values['dob'],
			'email' => $values['email'],
			'gender' => $values['gender'],
			'hobby' => $values['hobby'],
			'state' => $values['state'],
			'subject' => $values['subjects'],
			'image' => $filename

		];
	} else {
		$insertdata = [
			'name' => $values['name'],
			'dob' => $values['dob'],
			'email' => $values['email'],
			'gender' => $values['gender'],
			'hobby' => $values['hobby'],
			'state' => $values['state'],
			'subject' => $values['subjects'],

		];
	}



	$db = connectdb();
	if ($db->insert("task3_crud", $insertdata)) {

		$msg = array("message" => "Successfully Inserted", "status" => "true");
		return $response->withJson(array_merge($msg, $insertdata));
	} else {
		$msg = array("message" => "Didn't Insert The Values", "status" => "false");
		return $response->withJson($msg);
	}
});

//DELETE DATA API
$app->post('/delete', function (Request $request, Response $response) {

	$values = $request->getParsedBody();
	$id = $values['id'];
	remove_file($id);
	$db = connectdb();
	if ($db->delete('task3_crud', array('id' => $id))) {
		$msg = array("message" => "Successfully Deleted", "status" => "true");
		return $response->withJson($msg);
	} else {
		$msg = array("message" => "Can't Deleted The Record ", "status" => "true");
		return $response->withJson($msg);
	}
});


//DELETE MULTIPLE DATA API
$app->post('/multi-delete', function (Request $request, Response $response) {

	$values = $request->getParsedBody();
	$id = implode(',', $values['id']);

	for ($i = 0; $i < count($values['id']); $i++) {
		remove_file($values['id'][$i]);
	}
	$db = connectdb();
	if ($db->query("delete from task3_crud where id in ($id)")) {
		$msg = array("id" => "$id", "message" => "Successfully Deleted", "status" => "true");
		return $response->withJson($msg);
	} else {
		$msg = array("message" => "Can't Deleted The Record ", "status" => "true");
		return $response->withJson($msg);
	}
});


//UPDATE DATA API
$app->post('/update', function (Request $request, Response $response) {

	$values = $request->getParsedBody();
	$files = $request->getUploadedFiles();
	$myFile = $files['file'];

	$db = connectdb();
	$db->select("task3_crud", array("id" => "" . $values['id'] . ""), false, false, 'and', 'image');
	$result = $db->result_array();

	if ($myFile->getError() === UPLOAD_ERR_OK) {

		$filename = moveUploadedFile('Images/', $files['file']);
		// move_uploaded_file();
		remove_file($values['id']);

		$updatedata = [
			'name' => $values['name'],
			'dob' => $values['dob'],
			'email' => $values['email'],
			'gender' => $values['gender'],
			'hobby' => $values['hobby'],
			'state' => $values['state'],
			'subject' => $values['subjects'],
			'image' => $filename

		];
	} else {
		$filename = $result['0']['image'];
		$updatedata = [
			'name' => $values['name'],
			'dob' => $values['dob'],
			'email' => $values['email'],
			'gender' => $values['gender'],
			'hobby' => $values['hobby'],
			'state' => $values['state'],
			'subject' => $values['subjects'],
			'image' => $filename
		];
	}


	if ($db->update("task3_crud", $updatedata, array('id' => $values['id']))) {

		$msg = array("id" => "" . $values['id'] . "", "name" => "" . $values['name'] . "", "dob" => "" . $values['dob'] . "", "email" => "" . $values['email'] . "", "gender" => "" . $values['gender'] . "", "hobby" => "" . $values['hobby'] . "", "state" => "" . $values['state'] . "", "subject" => "" . $values['subjects'] . "", "image" => "" . $filename . "", "message" => "Successfully Updated", "status" => "true");
		return $response->withJson($msg);
	} else {
		$msg = array("message" => "Didn't Update The Values", "status" => "false");
		return $response->withJson($msg);
	}
});

//LIVE SEARCH API
$app->post('/live-search', function (Request $request, Response $response) {
	$values = $request->getParsedBody();
	$search_value = $values['value'];
	$page_no = $values['pg_no'];

	$limit_per_page = 5;
	$offset = ($page_no - 1) * $limit_per_page;

	$db = connectdb();
	$db->query("select *	 from task3_crud where name like '%$search_value%' or email like '%$search_value%' or state like '%$search_value%'");

	$data = $db->result();

	$pages = ceil(count($data) / $limit_per_page);


	$db->query("select * from task3_crud where name like '%$search_value%' or email like '%$search_value%' or state like '%$search_value%' limit $offset,$limit_per_page");

	$result = $db->result();

	$pages = ceil(count($data) / $limit_per_page);
	if ($data != NULL) {
		$msg = array("Message" => array("status" => "true", "pages" => "$pages"));
		return $response->withJson(array_merge($msg, $result));
	} else {
		$msg = array("message" => "No Record Found", "status" => "false");
		return $response->withJson($msg);
	}
});


//ASCENDING AND DESCENDING API
$app->post('/orderby', function (Request $request, Response $response) {
	$values = $request->getParsedBody();
	$col_value = $values['col_name'];
	$orderby = $values['ordrby'];
	$page_no = $values['pg'];

	$limit_per_page = 5;
	$offset = ($page_no - 1) * $limit_per_page;

	$db = connectdb();
	$db->query("select * from task3_crud order by $col_value $orderby limit $offset,$limit_per_page");




	$data = $db->result();
	$pages = no_of_page($limit_per_page);
	if ($data != NULL) {
		$msg = array("Message" => array("status" => "true", "pages" => "$pages"));
		return $response->withJson(array_merge($msg, $data));
	} else {
		$msg = array("message" => "No Record Found", "status" => "false");
		return $response->withJson($msg);
	}
});


//DOG API
$app->get('/changeimage', function (Request $request, Response $response) {

	$URL="https://dog.ceo/api/breeds/image/random";
	return tp_api_call($URL);
});

//WEATHER API
$app->post('/weather', function (Request $request, Response $response) {

	$URL="http://api.weatherapi.com/v1/forecast.json?key=8fc82d18aa5a49a4a96115003211410&q=ahmedabad";
	return tp_api_call($URL);
});
$app->run();
