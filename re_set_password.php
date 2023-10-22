<?php 
include_once __DIR__ . '/includes/connect.php';

if(empty($_SESSION['user'])){
	redirect($HOST."/index.php");
} else if(!$_SESSION['user']['reset']){
	redirect($HOST."/tasks.php");
}
 
include_once 'layout/header.php';

$error=array();
if(isset($_POST['user_login'])){
 
	$old_password= makeSafe($_POST['old_password']);
	$password = makeSafe($_POST['password']);
	$cpassword = makeSafe($_POST['cpassword']);

	if(empty($old_password)){
		$error['old_password']="Old Password is Required";
	}
	if(empty($password)){
		$error['password']="Password is Required";
	}
	if(empty($cpassword)){
		$error['cpassword']="Confirm Password is Required";
	}
	if(empty($error['password']) && empty($error['cpassword'])){
		if($password!=$cpassword){
			$error['cpassword']="Both Password must be same";
		}
	}

	if(empty($error)){
		 
		$selectSql="SELECT id,email,password,fname,lname,last_login,last_password_change 
		FROM users WHERE id=:user_id";
		$resEmail = $pdo->selectOne($selectSql,array(':user_id'=>$_SESSION['user']['id'])); 

		if(!empty($resEmail)){
			if (password_verify($old_password, $resEmail['password'])) { 
			 	
			 	$updateParams=array(
			 		'password'=> password_hash($password, PASSWORD_DEFAULT),
			 		'last_password_change'=>'msqlfunc_NOW()',
			 		'updated_at'=>'msqlfunc_NOW()'
			 	);
			 	$where=array(
			 		'clause'=>'id=:user_id',
			 		'params'=>array(
			 			':user_id'=>$_SESSION['user']['id']
			 		)
			 	);
			 	$pdo->update('users',$updateParams,$where);
 
			 	unset($_SESSION['user']);
				//redirect to 
				redirect($HOST."/index.php");

			} else {
				$error['password']= "Invalid Password";
			}
		} else {
			//invalid email 
			$error['email']= "This email is not found as User";
		}
	}
}


?>
<div class="container my-md-4">
      <div class="container-fluid">
      	<h4>Re-Set Password</h4><br/>
		<form class="row g-3" method="POST" enctype="multipart/form-data" action="">
		  <div class="col-md-6">
			  <div class="col-md-6">
			    <label for="old_password"  class="form-label">Old Password</label>
			    <input type="password" class="form-control" id="old_password" name="old_password" value="<?php echo !empty($old_password)?$old_password:'';?>">
			    <p class="error"><?php echo !empty($error['old_password'])?$error['old_password']:"";?></p>
			  </div>
			  <div class="col-md-6">
			    <label for="password"  class="form-label">Password</label>
			    <input type="password" class="form-control" id="password" name="password" value="<?php echo !empty($password)?$password:'';?>">
			    <p class="error"><?php echo !empty($error['password'])?$error['password']:"";?></p>
			  </div>
			  <div class="col-md-6">
			    <label for="cpassword"  class="form-label">Password</label>
			    <input type="password" class="form-control" id="cpassword" name="cpassword" value="<?php echo !empty($cpassword)?$cpassword:'';?>">
			    <p class="error"><?php echo !empty($error['cpassword'])?$error['cpassword']:"";?></p>
			  </div>
			</div>
		   
 
		  <div class="col-12">
		    <button type="submit" class="btn btn-primary" name="user_login">Re-set</button>
		     <br><br>
		    <!-- <a href="">Forgot Password?</a> -->
		  </div>
		</form>
</div>
</div>
<?php 
include_once 'layout/footer.php';
?>