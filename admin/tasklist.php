<?php 
include_once __DIR__ . '/includes/connect.php';

 
if(empty($_SESSION['admin'])){
	redirect($HOST."/index.php");
}
 
$sel_sql = "SELECT CONCAT(u.fname,' ',u.lname) as name,t.* 
FROM tasks t 
JOIN users u ON(u.id=t.user_id) 
WHERE t.id>0 ORDER BY t.id DESC";
$resTask = $pdo->select($sel_sql);

if (isset($_GET["export"]) && trim($_GET["export"]) != "") {
	$csv_line = "\n";
	$csv_seprator = "\t";
	$content = "User" . $csv_seprator . 
                      "Start time" . $csv_seprator .
                      "Stop Time" . $csv_seprator .
                      "Notes" . $csv_seprator .
                      "Description" . $csv_line;
    if(!empty($resTask)){
    	foreach($resTask as $csvrow){
    		$content .= $csvrow['name'] . $csv_seprator .
    					date('M/D/Y H:i:s',strtotime($csvrow['start_time'])) . $csv_seprator .
    					date('M/D/Y H:i:s',strtotime($csvrow['stop_time'])) . $csv_seprator .
    					$csvrow['note'] . $csv_seprator .
    					$csvrow['description'] . $csv_line;
    	}
    }

    if ($content) {
      header('Content-type: application/vnd.ms-excel');
      header('Content-disposition: attachment;filename=All_Tasks_' . date('YmdHis') . '.csv');
      echo $content;
      exit;
    }

}

include_once 'layout/header.php';
?>
<div class="container my-md-4">
      <div class="container-fluid">
      	<h3>Task List</h3>
      	<div class="d-grid gap-2 d-md-flex justify-content-md-end">
	      	<a class="btn btn-outline-dark" href="tasklist.php?export=yes" role="button">Download All Task CSV</a>
      	</div>
		<table class="table">
		  <thead>
		    <tr> 
		      <th scope="col">User</th>
		      <th scope="col">Start Time</th>
		      <th scope="col">Stop Time</th>
		      <th scope="col">Note</th> 
		    </tr>
		  </thead>
		  <tbody>
		  	<?php 
		  	if(!empty($resTask)){
		  		foreach($resTask as $rows){
		  			?>
		  			<tr> 
		  				<td><?php echo $rows['name'];?></td>
		  				<td><?php echo !empty($rows['start_time'])?date('m/d/Y H:i:s',strtotime($rows['start_time'])):'-';?></td>
		  				<td><?php echo !empty($rows['stop_time'])?date('m/d/Y H:i:s',strtotime($rows['stop_time'])):'-';?></td>
		  				<td><?php echo $rows['note'];?></td> 
		  			</tr>

		  			<?php
		  		}
		  	}
		     ?>
		  </tbody>
		  <?php /*if ($total_rows > 0) { ?>
		  	}
      <tfoot>
         <tr>
            <td colspan="9">
               <?php echo $paginate->links_html; ?>    
            </td>
         </tr>
      </tfoot>
      <?php } */?>  
		</table>
	</div>
</div>
<?php 
include_once 'layout/footer.php';
?>