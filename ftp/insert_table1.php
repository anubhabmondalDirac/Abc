<?php
     $serverName="204.93.178.157";
	 $db_type = 'mssql';
	$connectionInfo=array("Database"=>"rajarshi_cleartest","UID"=>"rajarshi_cleartest", "PWD"=>"12345");
	$conn = sqlsrv_connect($serverName,$connectionInfo);
	if($conn) {
		echo "Connectio Establish";
		
	} else {
		echo "Not Connect";
	}
	
	print_r($_POST);
    $column_array=$_POST['column'];
    $row_array=$_POST['row'];
	$check_array=$_POST['check'];
    $column_len=count($column_array);
    $row_len=count($row_array);
	$check_len=count($check_array);
	echo $row_len;
	//echo $column_len;
	//Drop Qes Table If Exists
  	$dropSQL= "DROP TABLE  matrix11";
	
	if(sqlsrv_query($conn, $dropSQL)){
    echo "Table Droped successfully.";
	} else{
		echo "ERROR: Could not able to execute $dropSQL. " . sqlsrv_errors($conn);
	}  
	//Create dynamic Table in DataBase
	$createSQL = "CREATE TABLE matrix11
              (".implode(" NVARCHAR(30), ", $column_array). " 
               NVARCHAR(30));";

	if(sqlsrv_query($conn, $createSQL)){
    echo "Table created successfully.";
	} else{
		echo "ERROR: Could not able to execute $createSQL. " . sqlsrv_errors($conn);
	}
	//Insert Table Value with Correct Answer
	
		for($x=0;$x<$row_len;$x++)
    {
		 //$insertSQL = "INSERT INTO matrix1($column_array[0]) VALUES ('$row_array[$x]')"; 
		 $insertSQL = "INSERT INTO matrix11(".implode(" , ", $column_array). " ) VALUES ('".$row_array[$x]."'";
		 for($j=0;$j<($column_len-1);$j++){
			$insertSQL .= ", '".$check_array[$x][$j]."'";
		 }
		 $insertSQL .=")"; 
		 if(sqlsrv_query($conn, $insertSQL)){
		echo "Table Data Inserted  successfully.";
		} 
	}
/***	
	//Generate JSON for Created Question
	$jsonSQL = "SELECT * FROM matrix1";
	$query = sqlsrv_query($conn, $jsonSQL);
	$rows = array();
	while($row = mysqli_fetch_assoc($query)) {
		$rows[] = $row;
	}
	print json_encode($rows);
	*/
	//Drop Clone table If Exists
    $dropSQL2= "DROP TABLE matrix21";
	
	if(sqlsrv_query($conn, $dropSQL2)){
    echo "clone Table Droped successfully.";
	} else{
		echo "ERROR: Could not able to execute $dropSQL2. " . sqlsrv_errors($conn);
	}
	
	
	//Clone Qes Table [matrix1]and Generate Student result table [matrix]
	//$createCloneTable= "CREATE TABLE matrix2 LIKE matrix1" ;
		//Answer table same as question 		   
	$createCloneTable=  "CREATE TABLE matrix21
              (".implode(" NVARCHAR(30), ", $column_array). " 
               NVARCHAR(30));";
	if(sqlsrv_query($conn, $createCloneTable)){
		echo "clone Table successfully.";
	}
	//Insert row name to clone table 
	$insertCloneTable = "insert into matrix21(R0C0) SELECT R0C0 FROM matrix11";
	if(sqlsrv_query($conn, $insertCloneTable)){
		echo "clone Table successfully.";
	}	
	
    $conn->close(); 
?> 