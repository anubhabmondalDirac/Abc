<?php
    $serverName="204.93.178.157";
	 $db_type = 'mssql';
	$connectionInfo=array("Database"=>"rajarshi_cleartest","UID"=>"rajarshi_cleartest", "PWD"=>"12345");
	$conn = sqlsrv_connect($serverName,$connectionInfo);
	/* if($conn) {
		echo "Connectio Establish";
		
	} else {
		echo "Not Connect";
	} */
	//print_r($_POST);

   // $conn->close(); 
?> 