<?php
/* Database connection start */
$servername = "localhost";

$username = "likejagg_salma";

$password = "xrep8HDqUTuJUbEe";

$dbname = "likejagg_SALMA";

$conn = mysqli_connect($servername, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());

/* Database connection end */


// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;
$section = $_REQUEST['section'];
$where = '';
$max = 11;

$sql = "";
// get initial data 
if( empty($requestData['columns'][0]['search']['value']) ){ 
	$sql = "SELECT * message from employees";
	$query=mysqli_query($conn, $sql) or die(mysqli_error($conn).' '.$sql);
	$totalData = mysqli_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
}
// initial of 10 rows
$where = " where members.id < ".$max ;

//else request search criteria
if( !empty($requestData['columns'][0]['search']['value']) ){ 
	//print_r($requestData);
	$where = ' WHERE 1' ;
	$sql = "SELECT * message from employees  ";

	//$where.=" AND (";
	$exp = explode('&',$requestData['columns'][0]['search']['value']);
	
	$temp = explode(':',$exp[0]);
	
	if ($temp[0] == 'fulltimestamp' && $temp[1] !=''){
		
		$range = explode('|',$temp[1]);		
		$start = trim($range[0]); //name
		$end = trim($range[1]); //name
		$where.=" AND fulltimestamp >= '".$start."' AND fulltimestamp < ('" .$end. "' + INTERVAL 1 DAY) ";
 
	}
	array_splice($exp, 0, 1);
	 
	foreach ($exp as $e){
		$arr = explode(':', $e);
		$key = trim($arr[0]);
		$val = trim($arr[1]);
		if ($key == 'transid' && $val !='')
			$where.=" AND (".$key." like '%" . $val ."%' or reference like '%".$val."%') ";
		
		else if ($key == 'utility_reference' && $val !='')
			$where.=" AND ".$key." like '%" . $val ."%'" ;
		else if ($key == 'result' && $val !='')
			$where.=" AND ".$key." like '%" . $val ."%'" ;
	
	
		
	}
	//$where.="  )";
	
   //print_r($where);

   $sql .= $where;
   //print_r($sql);
   $query2=mysqli_query($conn, $sql) or die(mysqli_error($conn).' '.$sql);
   $totalData = mysqli_num_rows($query2); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
   $totalFiltered = ($totalData);}


$sql.= " ORDER BY 0   ".$requestData['order'][0]['dir']."";
//Before adding limit find num_rows

$sql.= "  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
$query=mysqli_query($conn, $sql) or die(mysqli_error($conn).' '.$sql);
$rows = array();
$data = array();
while( $rows=mysqli_fetch_assoc($query) ) {  // preparing an array
	$nestedData=array();
foreach($rows as $row)

	$nestedData[] = $row;
	$data[] = $nestedData;
}



$json_data = array(
"query" => $sql,
"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
"recordsTotal"    => intval( $totalData ),  // total number of records
"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
"data"            => $data   // total data array
);

echo json_encode($json_data);  // send data as json format

?>
