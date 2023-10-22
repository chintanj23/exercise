<?php 
include_once __DIR__ . '/includes/connect.php';

if(!empty($_SESSION['admin'])){
	redirect($HOST."/users.php");
}

include_once 'layout/header.php';

$error=array();
if(isset($_POST['admin_login'])){
 
	$email= makeSafe($_POST['adminemail']);
	$password = makeSafe($_POST['password']);

	if(empty($email)){
		$error['email']="Email is Required";
	}
	if(empty($password)){
		$error['password']="Password is Required";
	}

	if(empty($error)){
		//check if email password in database 
		$selectSql="SELECT id,email,password,fname,lname FROM admin WHERE email=:email";
		$resEmail = $pdo->selectOne($selectSql,array(':email'=>$email));
 
		if (password_verify($password, $resEmail['password']) || md5($password)== $resEmail['password']) {
			//check password 
			if($resEmail['password']==md5($password)){
				//admin session 
				unset($resEmail['password']);
				$_SESSION['admin']=$resEmail;

				//redirect to 
				redirect($HOST."/users.php");

			} else {
				$error['password']= "Invalid Password";
			}
		} else {
			//invalid email 
			$error['email']= "This email is not found as Admin";
		}
	}
}


?>
<div class="container my-md-4">
      <div class="container-fluid">
		<form class="row g-3" method="POST" enctype="multipart/form-data" action="">
		  <div class="col-md-6">
			  <div class="col-md-6">
			    <label for="adminemail" class="form-label">Email</label>
			    <input type="email" class="form-control" id="adminemail" name="adminemail" value="<?php echo !empty($email)?$email:'';?>">
			    <p class="error"><?php echo !empty($error['email'])?$error['email']:"";?></p>
			  </div>
			  <div class="col-md-6">
			    <label for="password"  class="form-label">Password</label>
			    <input type="password" class="form-control" id="password" name="password" value="<?php echo !empty($password)?$password:'';?>">
			    <p class="error"><?php echo !empty($error['password'])?$error['password']:"";?></p>
			  </div>
			</div>
		   
 
		  <div class="col-12">
		    <button type="submit" class="btn btn-primary" name="admin_login">Login</button>
		     <br><br>
		    <!-- <a href="">Forgot Password?</a> -->
		  </div>
		</form>
</div>
</div>
<?php 
include_once 'layout/footer.php';
?>