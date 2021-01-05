<?php
 //include show.php;
    $serverName="204.93.178.157";
	 $db_type = 'mssql';
	$connectionInfo=array("Database"=>"rajarshi_cleartest","UID"=>"rajarshi_cleartest", "PWD"=>"12345");
	$conn = sqlsrv_connect($serverName,$connectionInfo);
	if($conn) {
		echo "Connectio Establish";
		
	} else {
		echo "Not Connect";
	}
	//print_r($_POST);
	
    $rowNum=$_POST['row1'];
	//$colNum = $_POST['col'];
	//echo $rowNum;
	//echo json_encode($rowNum);
	//print_r explode(" ,",$rowNum);
	$row_len=count($rowNum);
	//echo $row_len;
	/** Insert Comman seperated value to 'answer' table **/
	$deleteAnswer = "delete from answer11";
		if(sqlsrv_query($conn, $deleteAnswer)){
		       echo "Answer data deleted  successfully.";
	    }
	$splitFun = "CREATE FUNCTION [dbo].[Split] (
				@InputString                  VARCHAR(8000),
				@Delimiter                    VARCHAR(50)
			)

			RETURNS @Items TABLE (
				Item                          VARCHAR(8000)
			)

			AS
			BEGIN
				IF @Delimiter = ' '
				BEGIN
					  SET @Delimiter = ','
					  SET @InputString = REPLACE(@InputString, ' ', @Delimiter)
				END

				IF (@Delimiter IS NULL OR @Delimiter = '')
					SET @Delimiter = ','

				DECLARE @Item           VARCHAR(8000)
				DECLARE @ItemList       VARCHAR(8000)
				DECLARE @DelimIndex     INT

				SET @ItemList = @InputString
				SET @DelimIndex = CHARINDEX(@Delimiter, @ItemList, 0)
				WHILE (@DelimIndex != 0)
				BEGIN
					  SET @Item = SUBSTRING(@ItemList, 0, @DelimIndex)
					  INSERT INTO @Items VALUES (@Item)

					  SET @ItemList = SUBSTRING(@ItemList, @DelimIndex+1, LEN(@ItemList)-@DelimIndex)
					  SET @DelimIndex = CHARINDEX(@Delimiter, @ItemList, 0)
				END

				IF @Item IS NOT NULL
				BEGIN
					  SET @Item = @ItemList
					  INSERT INTO @Items VALUES (@Item)
				END

				ELSE INSERT INTO @Items VALUES (@InputString)

				RETURN

			END";
	if(sqlsrv_query($conn, $splitFun)){
		       echo "Split successfully.";
	       }		
    $insertSQL = "/* Get the length of the comma separated string */
			DECLARE @ITEM_COUNT INT

			SELECT @ITEM_COUNT = COUNT(*) FROM
			(
				SELECT  item
					FROM Split('$rowNum',',')  
			) N

			declare @x int
			set @x = 1

			/* Insert in your table every 3 columns... */
			WHILE (@x < @ITEM_COUNT)
			BEGIN 

				insert into answer11 
				select /* pivoting the sub-query */
				  fieldname = max(case when seq = @x then item end),
				  fieldcondition = max(case when seq = @x + 1 then item end),
				  fieldvalue = max(case when seq = @x + 2 then item end)
				from 
				(
					SELECT  item
						   ,row_number() OVER (ORDER BY (SELECT 1)) AS seq 
							FROM Split('$rowNum',',')  
				) a

			set @x = @x + 3

			END";	
     if(sqlsrv_query($conn, $insertSQL)){
		       echo "Table data insert successfully.";
	       }			
	/***Update student answer matrix21 from answer1  */
	
	$updateAnswer = "DECLARE @i INT
					DECLARE @j INT
					DECLARE @k NVARCHAR(50)
					DECLARE @row NVARCHAR(50)
					DECLARE @columnName NVARCHAR(50)
					DECLARE @execSQL NVARCHAR(100)
					DECLARE @x INT
					DECLARE @noOfRowsInAnswer INT
					SET @x = 1
					SET @noOfRowsInAnswer = (SELECT COUNT(*) FROM ANSWER11);
					WHILE ( @x <= @noOfRowsInAnswer )
					BEGIN

						WITH ANSWER1 AS 
						(
						  SELECT ROWNUM,COLNUM,VAL, ROW_NUMBER() OVER (ORDER BY ROWNUM) ROW_POSITION FROM ANSWER11
						)
						SELECT @i=ROWNUM,@j=COLNUM,@k=VAL FROM ANSWER1 WHERE ROW_POSITION=@x;
						WITH ROW1 AS
						(
						 SELECT R0C0, ROW_NUMBER() OVER (ORDER BY R0C0) ROWCOUNT1 from matrix21
						)
						SELECT @row = R0C0 FROM ROW1 WHERE ROWCOUNT1=@i-1;
						SELECT @columnName = COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS  WHERE TABLE_NAME = N'matrix21' and ORDINAL_POSITION=@j;
						SET @execSQL = 'UPDATE MATRIX21 SET '+ @columnName +' ='''+ @k +''' WHERE R0C0 = '''+@row+''''
						Select @execSQL
						EXECUTE(@execSQL)
						SET @x=@x+1
					END";
					
	 if(sqlsrv_query($conn, $updateAnswer)){
       echo "Table data update successfully.";
      }	
	  

    $updateNullToEmpty ="
					DECLARE @columnName NVARCHAR(50)
					DECLARE @execSQL NVARCHAR(100)
					DECLARE @x INT
					DECLARE @noOfRowsInAnswer INT
					SET @x = 0
					SET @noOfRowsInAnswer = (SELECT COUNT(*) FROM MATRIX21);
					WHILE ( @x <= @noOfRowsInAnswer )
					BEGIN
						SELECT @columnName = COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS  WHERE TABLE_NAME = N'matrix21' and ORDINAL_POSITION=@x+1;
						SET @execSQL = 'UPDATE MATRIX21 SET '+ @columnName +' ='''' where [' + @columnName + '] IS null' 
						Select @execSQL
						EXECUTE(@execSQL)
						SET @x=@x+1
            		END"; 
	if(sqlsrv_query($conn, $updateNullToEmpty)){
       echo "Table EMPTY data update successfully.";
      }				
	  
	  //Compare two table cell value to find correct or wrong answer
	 $quesSQL = "SELECT * FROM matrix11";
	 $ansSQL  = "SELECT * FROM MATRIX21";
	 
	$quesRes=sqlsrv_query($conn, $quesSQL);
	$ansRes=sqlsrv_query($conn, $ansSQL);
	$rowCount = sqlsrv_num_fields($quesRes);
	echo $rowCount;
	//$quesRow = sqlsrv_fetch_array($quesRes, SQLSRV_FETCH_BOTH);
	//echo $quesRow;
	$correct = 0;
	$wrong = 0;
       while (($ansRow = sqlsrv_fetch_array($ansRes, SQLSRV_FETCH_BOTH)) && ($quesRow = sqlsrv_fetch_array($quesRes, SQLSRV_FETCH_BOTH)))
          {
               for($i=0;$i<$rowCount;$i++)
                {
                        if($ansRow[$i]== $quesRow[$i]){
						  $correct++;
						  echo "correct: ";
						  echo $ansRow[$i];
						  echo " ";
						  echo $quesRow[$i];
						  echo '<br>';
						}else {
						  $wrong++;
						  echo "wrong:";
						  echo $ansRow[$i];
						  echo " ";
						  echo $quesRow[$i];
						  echo '<br>';
						}
                }
            }
			/***/
    $ansSQL1  = "SELECT * FROM MATRIX21";
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$stmt = sqlsrv_query( $conn, $ansSQL1 , $params, $options );

	$row_count = sqlsrv_num_rows( $stmt );
	echo "Rowss: ";
	echo $row_count;
	/***/			
    echo "Correct Answer: ";
	echo $correct-$row_count;
    echo "Wrong Answer: ";
    echo $wrong;	
	  /***/

?> 