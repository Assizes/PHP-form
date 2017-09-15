<style>
	.cars-list{
    	background-color: floralwhite;
   	}
    #embeded-page{
    	max-width: 700px;
        background-color: #233237;
		padding: 15px;
		margin: auto;
		z-index: 1;
		position: relative;
        box-shadow: 2px 2px 3px 1px #18121E;
        border-radius: 6px;
        top: 200px;
    }
    table > tbody > tr > td > form{
    	margin-bottom: 0;
    }
    .txt-color{
    	color: rgb(255, 184, 95);
    }
</style>
<?php

// connect to database
include 'connect.php';

if($_GET["section"]==="editCar"){
	include 'update.php';
}
if($updated || $deleted || $_GET["section"]==="carslist"){
	$_GET["section"] = "carslist";
    if($GLOBALS['msg']){
?>
		<div class='msg'><?php echo $GLOBALS['msg'];?></div>
<?php
        unset($GLOBALS['msg']);
	}
    if($GLOBALS['error']){
    	foreach($GLOBALS['error'] as $error){
?>
        	<div class='error'><?php echo $error;?></div>
<?php
        }
        unset($GLOBALS['error']);
    }
    // count records from the database
    $sql = "SELECT count(VIN) as `total` FROM CarsInfo";
    $quary = $db->query($sql);
    $result = $quary->fetchall(PDO::FETCH_ASSOC);
    $total = (int) $result[0]['total'];
    $limit = 5;
 
    // source inclusion
    require_once 'Pagination.class.php';
 
    // determine page (based on <_GET>)
    $page = isset($_GET['page']) ? ((int) $_GET['page']) : 1;

    // instantiate; set current page; set number of records
    $pagination = (new Pagination());
    $pagination->setCurrent($page);
    $pagination->setTotal($total);
    $pagination->setRPP($limit);

    // grab rendered/parsed pagination markup
    $markup = $pagination->parse();

    // Get data from the database
    $sql = "SELECT VIN, Make, Model, Year, Mileage, Price FROM CarsInfo ORDER BY VIN DESC LIMIT ".($limit*($page-1)).",".$limit;
    $quary = $db->query($sql);
    $result = $quary->fetchall(PDO::FETCH_ASSOC);

    if ($result){        
?>

<!-- output data of each row -->
	<div align='center'>
		<table class="table table-bordered table-hover table-striped .table-condensed cars-list">
			<tr><th>VIN</th><th>Make</th><th>Model</th><th>Year</th><th>Milleage</th><th>Price</th><th colspan="2"></th></tr>
<?php
		foreach($result as $row){
?>
			<tr>
            	<td><?php echo $row["VIN"];?></td>
                <td><?php echo $row["Make"];?></td>
                <td><?php echo $row["Model"];?></td>
                <td><?php echo $row["Year"];?></td>
                <td><?php echo $row["Mileage"];?></td>
                <td><?php echo $row["Price"];?></td>
                <td><form action='?section=editCar' method='POST'>
                        <input type='hidden' name='VIN' value='<?php echo $row["VIN"];?>'/>
                        <input type='submit' class='btn btn-success' value='Edit'>
                    </form>
                </td>
                <td><form action='?section=deleteCar' method='POST' onsubmit="return validate()">
                        <input type='hidden' name='VIN' value='<?php echo $row["VIN"];?>'/>
                        <input type='submit' class='btn btn-danger' value='Delete'>
                    </form>
                </td>
            </tr>
<?php
		}
    	echo "</table></div>";
    	echo "<div align='center'>";
        echo $markup;
        echo "</div>";
	}
    else{
    	echo "0 results";
    }
?>
<br>
<div align="center">
	<a href="?section=cars">Insert another Car</a>
</div>
<script>
	function validate(){
    	if (confirm('Are you sure you want to delete this record?')){
        	return true;
        }
        else{
        	return false;
        } 
    }
</script>
<?php
}