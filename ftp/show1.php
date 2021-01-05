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
	
	$sql = "SELECT * FROM matrix21";
	//echo "result1";
	$result=sqlsrv_query($conn, $sql);

	$rowNum = sqlsrv_num_fields($result);
	//echo $rowNum ;
	echo '<form action="answer_table1.php" id="my_form" name="my_form" method="post">';
	 //$result= mysqli_query($conn, $sql);
        if($result)
        {
            echo '<table border=1 style="border-collaspe=collaspe" class="table table-striped table-bordered table-hover dataTable no-footer">';
            echo '<tr>';
			$q1 = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N'matrix21'";
			$result1=sqlsrv_query($conn, $q1);
			while ($row1 = sqlsrv_fetch_array($result1, SQLSRV_FETCH_NUMERIC)) 
			{
			  /* echo $row1[0];
			  echo $row1[1];
			  echo $row1[2]; */
			  echo "<th>";
			   for ($i=0;$i<$rowNum;$i++){
				  echo $row1[$i];
			  } 
			  echo "</th>";
			  
			}
			/****/
            echo '</tr>';
            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_NUMERIC))
            {
				//$rows[] = $row;
                echo '<tr>';
                for($i=0;$i<$rowNum;$i++)
                {
                        if($row[$i]==NULL){
						  echo '<td><input type="text" name=text[] id=text1 value= "" onchange="myFunction1(this.value);" /></td>';
						 // echo '<td><input type="text" name=text[] id=text1 value= "" /></td>';
						}else {
							echo '<td>'.$row[$i].'</td>';
						}
                }
                echo '</tr>';
            }

            echo '</table>';
        }
		echo '<input type="hidden" name="row1" id="row1" value="">';
			
		echo '<input type="submit" name="save" value="Submit Answer" onclick="myFunction()">';
		
		echo '</form>';
		
?>
<script>
var table = document.getElementsByTagName("table")[0];
var cells = table.getElementsByTagName("td"); // 
//var inputF = table.getElementById("id1"); 
var rowArray = [];
var colArray = [];
var textValue;
var cellIndex;
var rowIndex;
for(var i = 1; i < cells.length; i++){
    // Cell Object
    var cell = cells[i];
	
	
    // Track with onchange
    cell.onchange = function(){
        cellIndex  = this.cellIndex + 1;  
        rowIndex = this.parentNode.rowIndex + 1;
		rowArray.push(rowIndex,cellIndex);
    }

}

 function myFunction1(val) {
  textValue=val;
  rowArray.push(textValue);
 }  
  
 function myFunction() {
  document.getElementById("row1").value = rowArray;
  console.log("In MyFunction ");
 }
</script>
</body>
</html>