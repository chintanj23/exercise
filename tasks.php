<?php 
include_once __DIR__ . '/includes/connect.php';

 
if(empty($_SESSION['user'])){
	redirect($HOST."/index.php");
}
 
$sel_sql = "SELECT start_time,stop_time,note  
FROM tasks 
WHERE user_id=:user_id ORDER BY id DESC";
$resTask = $pdo->select($sel_sql,array(':user_id'=>$_SESSION['user']['id']));
 

include_once 'layout/header.php';
?>
<div class="container my-md-4">
      <div class="container-fluid">
      	<h3>Tasks</h3>
      	<div class="d-grid gap-2 d-md-flex justify-content-md-end">
	      	<a class="btn btn-outline-dark" href="add_task.php" role="button">Add Task</a>
      	</div>
		<table class="table">
		  <thead>
		    <tr>  
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