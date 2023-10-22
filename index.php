<?php 
include_once __DIR__ . '/includes/connect.php';

if(!empty($_SESSION['user'])){
	redirect($HOST."/tasks.php");
}
 
include_once 'layout/header.php';

$error=array();
if(isset($_POST['user_login'])){
 
	$email= makeSafe($_POST['email']);
	$password = makeSafe($_POST['password']);

	if(empty($email)){
		$error['email']="Email is Required";
	}
	if(empty($password)){
		$error['password']="Password is Required";
	}

	if(empty($error)){
		//check if email password in database 
		$selectSql="SELECT id,email,password,fname,lname,last_login,last_password_change 
		FROM users WHERE email=:email";
		$resEmail = $pdo->selectOne($selectSql,array(':email'=>$email)); 

		if(!empty($resEmail)){

			if (password_verify($password, $resEmail['password']) || md5($password)== $resEmail['password']) { 

				//user session 
				unset($resEmail['password']);
				$_SESSION['user']=$resEmail;

				//update last login
				$updateParams=array(
			 		'last_login'=>'msqlfunc_NOW()',
			 		'updated_at'=>'msqlfunc_NOW()',
			 	);
			 	$where=array(
			 		'clause'=>'id=:user_id',
			 		'params'=>array(
			 			':user_id'=>$_SESSION['user']['id']
			 		)
			 	);
			 	$pdo->update('users',$updateParams,$where);

				//check if password reset 
				$_SESSION['user']['reset']=false; 
				if(empty($resEmail['last_password_change'])){ 
					$_SESSION['user']['reset']=true;
					$_SESSION['user']['reset_message']="Generate your new password";
				} else { 
					//check last reset date
					$date1=date_create(DATE('Y-m-d H:i:s'));
					$date2=date_create($resEmail['last_password_change']);
					$diff=date_diff($date1,$date2);
					$days =$diff->format("%a");

					if($days>=30){
						$_SESSION['user']['reset']=true;
						$_SESSION['user']['reset_message']="30 Days have passed since Your last password change.";
					}
				}

				if($_SESSION['user']['reset']){ 
					redirect($HOST."/re_set_password.php");
				}
 

				//redirect to 
				redirect($HOST."/tasks.php");

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
      	<h4>LogIn</h4> <br/>
		<form class="row g-3" method="POST" enctype="multipart/form-data" action="">
		  <div class="col-md-6">
			  <div class="col-md-6">
			    <label for="email" class="form-label">Email</label>
			    <input type="email" class="form-control" id="email" name="email" value="<?php echo !empty($email)?$email:'';?>">
			    <p class="error"><?php echo !empty($error['email'])?$error['email']:"";?></p>
			  </div>
			  <div class="col-md-6">
			    <label for="password"  class="form-label">Password</label>
			    <input type="password" class="form-control" id="password" name="password" value="<?php echo !empty($password)?$password:'';?>">
			    <p class="error"><?php echo !empty($error['password'])?$error['password']:"";?></p>
			  </div>
			</div>
		   
 
		  <div class="col-12">
		    <button type="submit" class="btn btn-primary" name="user_login">Login</button>
		     <br><br>
		    <!-- <a href="">Forgot Password?</a> -->
		  </div>
		</form>
</div>
</div>
<?php 
include_once 'layout/footer.php';
?>