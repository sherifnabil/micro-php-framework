<?php


//get title function v1.0
 if(!isset($noNavbar)){
}


/*
**** function to to display All Records from database v1
****
***
**
*/
function getAll($tName, $order){
	global $con;
	$getAll = $con->prepare("SELECT * FROM $tName ORDER BY $order DESC");
	$getAll->execute();	
	$all = $getAll->fetchAll();
	return $all;
}


/*
**** function to to display Parent Catedories from database v1
****
***
**
*/
function getparent(){
	global $con;
	$getAll = $con->prepare("SELECT * FROM categories WHERE parent = 0 ORDER BY ID ASC");
	$getAll->execute();	
	$parent = $getAll->fetchAll();
	return $parent;
}


//session user variable
$sessionUser = '';
if(isset($_SESSION['user'])){
	$sessionUser = $_SESSION['user'];
}


//  function to check if the item is exist in databas v1.0
function checkitem($select, $dbtable, $value)  {
	global $con;
	$statement = $con->prepare(" SELECT $select FROM $dbtable WHERE $select = ? ");
	$statement->execute(array($value));
	$count = $statement->rowCount();
	return $count;

}





//redirect functuin v1.0

function redirecting($msg, $url = NULL, $seconds = 3){

	if ($url === NULL){
			$url = 'index.php';
			$link =  'Homepage';
		}else{
			  if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '' ){
			  
			  		$url = $_SERVER['HTTP_REFERER']; 	

			  		$link = 'Previous page';
			  	}else{
			  		$url = 'index.php';
					$link =  'Homepage';
			  	}
		}

		echo $msg;
		echo "<div class='alert alert-info'>you will be Redirected to " . $link . " after " . $seconds . " seconds</div>";

		header("refresh:" . $seconds . ";url='" . $url . "'");




}




///////function counts items by counting rows in the database

function countitem($item, $table){
	global $con;
	$stmt2 = $con->prepare("SELECT COUNT('$item') FROM $table");
	$stmt2->execute();
	 return $stmt2->fetchColumn();
}


/*
**** function to to display the latest items
****
***
**
*/
function latestitems($select, $table, $order, $limit){
	global $con;
	$ndstatement = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
	$ndstatement->execute();	
	$rows = $ndstatement->fetchAll();
	return $rows;

}



/*
**** function to to display the  categories from database v1
****
***
**
*/
function getcat($pare){
	global $con;
	$categories = $con->prepare("SELECT * FROM categories WHERE parent = ? ORDER BY ID ASC");
	$categories->execute(array($pare));	
	$cats = $categories->fetchAll();
	return $cats;

}


/*
**** function to to display the  categories from database v1
****
***
**
*/
function getccat($tag){
	global $con;
	$categories = $con->prepare(" SELECT * FROM items WHERE tags LIKE '%$tag%' AND Approve = 1 ORDER BY item_ID ASC ");
	$categories->execute();	
	$cats = $categories->fetchAll();
	return $cats;

}



/*
**** function to to display the  categories from database v1
****
***
**
*/
function getcatt(){
	global $con;
	$categories = $con->prepare("SELECT * FROM categories ORDER BY ID ASC");
	$categories->execute();	
	$cats = $categories->fetchAll();
	return $cats;

}



/*
**** function to to Add the  items from database v2
****
***
**
*/
function getItems($where, $value, $approve = NULL){
	global $con;
	if($approve == NULL) { $sql = 'AND Approve = 1';}else{$sql = '';}
	$getItems = $con->prepare("SELECT * FROM items WHERE $where = ? $sql ORDER BY item_ID DESC");
	$getItems->execute(array($value));	
	$items = $getItems->fetchAll();
	return $items;


}

	//check if user is in database
	function checkUserstat($user) {
		global $con;
	$stmtc = $con->prepare("SELECT 
									 username, RegStatus 
								FROM 
									users 
								WHERE 	

									RegStatus = 0  
								" );
		$stmtc->execute(array($user));
		$status = $stmtc->rowCount();
		return $status;
		}