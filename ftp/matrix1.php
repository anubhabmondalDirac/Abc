<!DOCTYPE HTML>

<html>
<head>
	
	<title>Matrix Type Question</title>
</head>
<style>
.table-content {
  padding: 20px;
}
.remove {
  margin-left: 10px;
  color: red;
}
.remove:hover {
  cursor: pointer;
}
.form-control {
  width: 90px;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet" />

<script>
$(document).ready(function() {
  var cols1;
  var row_num=0;
  // add row
  $('body').on('click', '.add-row', function() {
    var tr = $(this).parents('.table-content').find('.table tbody tr:last');
    var row_nums = $(this).parents('.table-content').find('tr').length; 
	
    if (row_nums > 0) { 
	   row_num = $('.table tbody tr').length; 
	   var ex_row= (row_num-1);
	  console.log("row==", row_num);
      var clone = tr.clone();
      clone.find(':text').val('').attr('name','row[]');
	  //clone.find('input[type=text]').attr('name','act_name'+col_num+'[]');
      tr.after(clone);
	  var chk_num = $(this).parents('.table-content').find('tr:last').find('input[type=textarea]').length;
	  for (var c = 0; c < chk_num; c++) {
		  $(this).parents('.table-content').find('tr:last').find('input[type=textarea]').eq(c).attr('name','check['+row_num+']['+c+']');
	  }
    } else { alert('111');
       var cols = $(this).closest('.table-content').find('th').length,
        tr0 = $('<tr/>');
      tr0.html('<td><span class="remove remove-row">x</span></td><td> <input type="text"  class="form-control"> </td>');
      for (var i = 2; i < cols; i++) {
        tr0.append('<td><input type="textarea" name="check['+ex_row+']['+i+']" value=""/></td>');
      }
      $(this).closest('.table-content').find('.table tbody').append(tr0);
      //$(this).closest('.table-content').find('.table tbody').append('<tr> <td><span class="remove remove-row">x</span></td><td> <input type="text" class="form-control"> </td><td> static element </td><td> static element </td></tr>');
    }
	
  
  });

  // delete row
  $('body').on('click', '.remove-row', function() {
    $(this).parents('tr').remove();
  });

  // add column
  $('body').on('click', '.add-col', function() {
	 cols1 = $('.table-content').find('th').length-2;
	console.log("col==", cols1);
    $(this).parent().find('.table thead tr').append('<th><input type="text" name="column[]" class="form-control pull-left" value=""> <span class="pull-left remove remove-col">x</span></th>');
    var len = $(this).parent().find('.table tbody tr').length-1; 
	for (var s = 0; s <= len; s++) {
	$(this).parent().find('.table tbody tr').eq(s).append('<td><input name ="check['+s+']['+cols1+']" type="textarea" value=""></td>');
	}
	//$(this).parent().find('.table tbody tr').append('<td><input type="checkbox"></td>').attr('name','check'+row_num+''+cols1+'');
  });

  // remove column
  $('body').on('click', '.remove-col', function(event) {
    // Get index of parent TD among its siblings (add one for nth-child)
    var ndx = $(this).parent().index() + 1;
    // Find all TD elements with the same index
    $('th', event.delegateTarget).remove(':nth-child(' + ndx + ')');
    $('td', event.delegateTarget).remove(':nth-child(' + ndx + ')');
  });
});
</script>
<body>
 <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.11.2.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <h3>Add a Matrix Type Question </h3>
    <div class="table-content">
      <button class="btn btn-link add-col">Add Column</button>
      <div class="table-responsive">
	  <form action="insert_table1.php" id="my_form" name="my_form" method="post" >
        <table class="table">
          <thead>
            <tr>
              <th></th>
              <th><input name="column[]" type="text" class="form-control pull-left" value="R0C0"></th>
              <th>
                <input name="column[]" type="text" class="form-control pull-left" value="C1">
                <span class="pull-left remove remove-col">x</span>
              </th>
              <th>
                <input name="column[]" type="text" class="form-control pull-left" value="C2">
                <span class="pull-left remove remove-col">x</span>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><span class="remove remove-row">x</span></td>
              <td>
                <input type="text" name="row[]" class="form-control">
              </td>
              <td>
               <input type="textarea" name="check[0][0]">
              </td>
              <td>
                <input type="textarea" name="check[0][1]">
              </td>
            </tr>
          </tbody>
        </table>
		<input type="submit" name="save" value="Submit">            
       </form>
		 
      </div>
      <button class="btn btn-link add-row">Add row</button> <br/>
	  
    </div>
</body>


</html>