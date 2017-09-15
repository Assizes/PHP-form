<!DOCTYPE html>
<html lang="en">
<html>
  <head>
    <title>Insert Car</title>
        <style>
    	body{
    		background-color: #d0e4fe
    	}</style>
  </head>
<body>

<?php

// connect to database
include 'connect.php';
//check if record exist
$sql = "SELECT count(`VIN`) as `count` FROM CarsInfo WHERE VIN=:VIN";
$res = $db->prepare($sql);
$res->execute(array(":VIN"=>$_POST['VIN']));
$result = $res->fetch(PDO::FETCH_ASSOC);

if($_POST['VIN'] === ''){
	$GLOBALS['error']['VIN'] = 'VIN is missing';
}
if($_POST['Make'] === ''){
	$GLOBALS['error']['Make'] = 'Make is missing';
}
if($_POST['Model'] === ''){
	$GLOBALS['error']['Model'] = 'Model is missing';
}
if((int)$_POST['Year'] < 1899 || (int)$_POST['Year'] > (int)date("Y")){
	$GLOBALS['error']['Year'] = 'Year should be in range of 1900 to '.date("Y");
    if($_POST['Year'] === ''){
    	$GLOBALS['error']['Year'] = 'Year is missing';
    }
}
if($_POST['Make'] === ''){
	$GLOBALS['error']['Mileage'] = 'Mileage is missing';
}
if($_POST['Price'] === ''){
	$GLOBALS['error']['Price'] = 'Price is missing';
}

if(!$GLOBALS['error']){
	if($result['count'] === '0'){
		// Insert data into database
        $params = array(":VIN"=>$_POST['VIN'], ":Make"=>$_POST['Make'], 
        	          ":Model"=>$_POST['Model'], ":Year"=>$_POST['Year'], 
                      ":Mileage"=>$_POST['Mileage'], ":Price"=>$_POST['Price']);
        $sql = "INSERT INTO CarsInfo VALUES (:VIN, :Make, :Model, :Year, :Mileage, :Price)";
        $res = $db->prepare($sql);
        $result = $res->execute($params);

        if ($result){
        	$GLOBALS['msg'] = "New record created successfully.";
        }else{
        	$GLOBALS['error']['sql'] = "Error: " . $sql;
       	}
	}else{
    	$GLOBALS['error']['dublicate'] = "Error: " . "Record with this VIN already exist.";   
    }
}

?>
</body>
</html>
