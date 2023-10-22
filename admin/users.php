<?php 
include_once __DIR__ . '/includes/connect.php';

 
if(empty($_SESSION['admin'])){
	redirect($HOST."/index.php");
}

include_once 'layout/header.php';


$sel_sql = "SELECT * FROM users WHERE id>0 ORDER BY id DESC";
$resUsers = $pdo->select($sel_sql);
?>
<div class="container my-md-4">
      <div class="container-fluid">
      	<h3>Employees</h3>
      	<div class="d-grid gap-2 d-md-flex justify-content-md-end">
	      	<a class="btn btn-outline-dark" href="add_user.php" role="button">Add New Employee</a>
      	</div>
		<table class="table">
		  <thead>
		    <tr>
		      <th scope="col">#</th>
		      <th scope="col">First Name</th>
		      <th scope="col">Last Name</th>
		      <th scope="col">Email</th>
		      <th scope="col">Phone</th>
		      <th scope="col">Last Password Change</th>
		      <th scope="col">Last Login</th>
		      <th scope="col">Action</th>
		    </tr>
		  </thead>
		  <tbody>
		  	<?php 
		  	if(!empty($resUsers)){
		  		foreach($resUsers as $rows){
		  			?>
		  			<tr>
		  				<td><?php echo $rows['id'];?></td>
		  				<td><?php echo $rows['fname'];?></td>
		  				<td><?php echo $rows['lname'];?></td>
		  				<td><?php echo $rows['email'];?></td>
		  				<td><?php echo $rows['phone'];?></td>
		  				<td><?php echo !empty($rows['last_password_change'])?date('m/d/Y H:i:s',strtotime($rows['last_password_change'])):'-';?></td>
		  				<td><?php echo !empty($rows['last_login'])?date('m/d/Y H:i:s',strtotime($rows['last_login'])):'-';?></td>
		  				<td><a href="add_user.php?id=<?php echo $rows['id'];?>">Edit</a></td>
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