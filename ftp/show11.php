<!DOCTYPE HTML>

<html>
<head>
	
	<title>Matrix Question</title>
	  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
<h3><center>Matrix Question</center></h3>
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
	
	$sql = "SELECT * FROM matrix1";
    //$result1 = $conn->query($sql);
	echo "result1";
	$result1=sqlsrv_query($conn, $sql);

	$rowNum = sqlsrv_num_fields($result1);
	echo $rowNum ;
	/* $result2 = mysqli_query($conn,"SELECT count(*) AS count FROM matrix2");
	$row = mysqli_fetch_array($result2);
	$colNum = $row['count']; */
	//echo $colNum;
	
	
	createTable_from_sql_select_query($conn, 'SELECT * FROM `matrix1`',$rowNum);
function createTable_from_sql_select_query($sql_link, $query,$rowNum) {

    $result=sqlsrv_query($sql_link, $query);
	echo "function";
	/*$sql = "SELECT * FROM matrix2";
	/*$sql = "SELECT * FROM matrix2";
	$result1 = $conn->query($sql);

	$rowNum = mysqli_num_fields($result1); */

    //if ($result->num_rows > 0) 
	if ($rowNum > 0) 
    {
		echo "<form action='student_table.php' id='my_form' name='my_form' method='post' >";
        echo "<table id='tbl' class='table table-bordered'><tr>";

        $field = $result->fetch_fields();
        $fields = array();
        $j = 0;
        foreach ($field as $col)
        {
            echo "<th>".$col->name."</th>";
            array_push($fields, array(++$j, $col->name));
        }
        echo "</tr>";

        while($row = $result->fetch_array()) 
        {
            echo "<tr>";
            for ($i=0 ; $i < sizeof($fields) ; $i++)
            {
                $fieldname = $fields[$i][1];
                $filedvalue = $row[$fieldname];
				
				if($filedvalue=='1' || $filedvalue==''){
					echo "<td><input name='check[$i][$p]' type='checkbox' value='1'></td>";
				} else {
                echo "<td>" . $filedvalue . "</td>";
				
				}
					
				
				
                
            }/
            echo "</tr>";
        }
        echo "</table>";
		echo "<input type='submit' name='save' value='Submit Answer'>";
		echo "</form>";

    } else {
		echo "Else Section";
	}
}
?>
</body>
</html>