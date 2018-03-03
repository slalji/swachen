<?php
include "../../config.php";
/* Database connection start */

$servername = "localhost";

$username =  DB_USERNAME;
$password = DB_PASSWORD;
//$password = "xrep8HDqUTuJUbEe";
$dbname = DB_NAME;
$conn = mysqli_connect($servername, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());

/* Database connection end */


// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;
$section = $_REQUEST['section'];
$where = '';

$sql = "SELECT * from employees";

if( !empty($requestData['columns'][0]['search']['value']) ){ 

	$where = ' WHERE 1' ;
	$sql = "SELECT * from employees ";

	//$where.=" AND (";
	$exp = explode('&',$requestData['columns'][0]['search']['value']);
	
	$temp = explode(':',$exp[0]);
	
	if ($temp[0] == 'created_at' && $temp[1] !=''){
		
		$range = explode('|',$temp[1]);		
		$start = trim($range[0]); //name
		$end = trim($range[1]); //name
		$where.=" AND created_at >= '".$start."' AND created_at < ('" .$end. "' + INTERVAL 1 DAY) ";
 
	}
	
	//$where.="  )";
	
   //print_r($where);

   $sql .= $where;
   //print_r($sql);
   $query2=mysqli_query($conn, $sql) or die(mysqli_error($conn).' '.$sql);
   $totalData = mysqli_num_rows($query2); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
   $totalFiltered = ($totalData);
}




$sql.= " ORDER BY created_at  desc";
//Before adding limit find num_rows

$result=mysqli_query($conn, $sql) or die(mysqli_error($conn).' '.$sql);
//$rows=mysqli_fetch_assoc($query);

$finfo = mysqli_fetch_fields($result);//_fetch_assoc($result);

$headers = array();
foreach ($finfo as $val) {
    $headers[] = $val->name;
}

$fp = fopen('php://output', 'w');
if ($fp && $result) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="data.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');
	fputcsv($fp, $headers);
	 
	//print_r($rows);
	
	while( $rows=mysqli_fetch_assoc($result) ) {  // preparing an array
		fputcsv($fp, $rows);
	}
}
?>
