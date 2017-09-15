<style>
	body{
    	background-color: #d0e4fe
  	}
    .update > table > tbody > tr > td > span{
    	color: #FFB85F;
    }
    td{
    	padding-top: 2px;
        padding-bottom: 2px;
        padding-right: 8px;
	}
    input:focus{
    	box-shadow: 0px 0px 4px 1px #b0dbfb;
    }
</style>
<?php
if($_POST['Make'] === ''){
	$GLOBALS['error']['Make'] = 'Make is missing';
}
if($_POST['Model'] === ''){
	$GLOBALS['error']['Model'] = 'Model is missing';
}
if((int)$_POST['Year'] < 1899 || (int)$_POST['Year'] > (int)date("Y")){
	$GLOBALS['error']['Year'] = 'Year should be in range from 1900 to '.date("Y");
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

$showform = true;
if(isset($_POST['VIN'])){
	$id=$_POST['VIN'];
	if(isset($_POST['submit']) && !$GLOBALS['error']){
    	$sql = "UPDATE CarsInfo SET Make=:Make, Model=:Model, Year=:Year, Mileage=:Mileage, Price=:Price  WHERE VIN=:VIN";
        $params = array(":VIN"=>$_POST['VIN'], ":Make"=>$_POST['Make'],
                  ":Model"=>$_POST['Model'], ":Year"=>$_POST['Year'],
                  "Mileage"=>$_POST['Mileage'], ":Price"=>$_POST['Price']);
        $res = $db->prepare($sql);
        $result = $res->execute($params);
        if ($result){
        	$GLOBALS['msg'] = "Record updated successfully.";
            $showform = false;
            $updated = true;
        }else{
        	$GLOBALS['error']['sql'] = "Error: " . $sql;
            $showform = true;
            $updated = false;
        }
	}
	if($showform){
    	if($GLOBALS['error']){
        	foreach($GLOBALS['error'] as $error){
?>
            	<div class='error'><?php echo $error;?></div>                       
<?php        
			}
            unset($GLOBALS['error']);
       	}
		$sql = "select * from CarsInfo where VIN=:VIN";
        $res = $db->prepare($sql);
        $quary = $res->execute(array(":VIN"=>$_POST["VIN"]));
        $result = $res->fetch(PDO::FETCH_ASSOC);
?>
		<form class='update' method='POST' action='?section=editCar'>
			<input type='hidden' name='VIN' value='<?php echo $result["VIN"]; ?>'></input>
			<table border="0" cellpadding="4" align='center'>
				<tr>
					<td><span> Make:</span></td>
					<td><input type='text' name='Make' value='<?php echo $result["Make"]; ?>'/></td>
				</tr>
				<tr>
					<td><span>Model:</span></td>
					<td><input type='text' name='Model' value='<?php echo $result["Model"] ?>'/></td>
				</tr>
				<tr>
					<td><span>Year:</span></td>
					<td><input type='text' name='Year' value='<?php echo $result["Year"];?>'/></td>
				</tr>
				<tr>
					<td><span>Mileage:</span></td>
					<td><input type='text' name='Mileage' value='<?php echo $result["Mileage"]; ?>'/></td>
				</tr>
				<tr>
					<td><span>Price:</span></td>
					<td><input type='text' name='Price' value='<?php echo $result["Price"]; ?>'/></td>
				</tr>
			</table>
			<br />
			<div align='center'><input class='btn btn-success' type='submit' name='submit' value='Update' /></div>
		</form>
<?php
	}
}
?>