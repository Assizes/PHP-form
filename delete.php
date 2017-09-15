<?php
// connect to database
include 'connect.php';

// Delete row from the database] 
$params = array(':VIN' => $_POST['VIN']);
$sql = "DELETE FROM CarsInfo WHERE VIN = :VIN";
$res = $db->prepare($sql);
$result = $res->execute($params);

if ($result){
    $GLOBALS['msg'] = "Record was deleted successfully.";
    $deleted = true;
}else{
    $GLOBALS['error']['delete'] = "Error: " . $sql;
}

?>