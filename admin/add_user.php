<?php 
include_once __DIR__ . '/includes/connect.php';


function random_str(
    $length,
    $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
) {
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    if ($max < 1) {
        throw new Exception('$keyspace must be at least two characters long');
    }
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
    return $str;
}
 
if(empty($_SESSION['admin'])){
	redirect($HOST."/index.php");
}

//check if edit user 
if(!empty($_GET['id'])){
	$user_id = makeSafe($_GET['id']);

	$selectuser="SELECT * FROM users WHERE id=:user_id";
	$userRow = $pdo->selectOne($selectuser,array(':user_id'=>$user_id));

	if(empty($userRow)){
		redirect($HOST.'/users.php');
	}

	$fname= $userRow['fname'];
	$lname = $userRow['lname'];
	$email = $userRow['email'];
	$phone = $userRow['phone'];
}

$error=array();
if(isset($_POST['adduser'])){
 
	$user_id= makeSafe($_POST['user_id']);
	$fname= makeSafe($_POST['fname']);
	$lname = makeSafe($_POST['lname']);
	$email = makeSafe($_POST['email']);
	$phone = makeSafe($_POST['phone']);
	$auto_password = makeSafe($_POST['auto_password']);

	if(empty($fname)){
		$error['fname']="First Name is required";
	}
	if(empty($lname)){
		$error['lname']="Last Name is required";
	}
	if(empty($email)){
		$error['email']="Email is required";
	}
	if(empty($phone)){
		$error['phone']="Phone Number is required";
	}
	if(empty($auto_password)){
		$error['password']="Password is required";
	}

	if(empty($error['phone'])){
		if(!is_numeric($phone)){
			$error['phone']="Only digit allow";
		}
	}
	if(empty($error['email'])){
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$error['email']="Invalid email";
		}
	}
	//check if email is already registered 
	if(empty($error['email'])){
		$tmpl="";
		$where=array();
		if(!empty($user_id)){
			$tmpl=" AND id!=:id";
			$where[':id']=$user_id;
		}
		$selectUser="SELECT id FROM users WHERE email=:email".$tmpl;
		$where[':email']=$email;
		$resUser = $pdo->selectOne($selectUser,$where);
		if(!empty($resUser)){
			$error['email']="User already Exist";
		}
	}

	//add user 
	if(empty($error)){
		$params=array(
			'fname'=>$fname,
			'lname'=>$lname,
			'email'=>$email,
			'phone'=>$phone,
			'password'=>password_hash($auto_password, PASSWORD_DEFAULT)
		);

		if(!empty($userRow)){
			$where=array(
				'clause'=>'id = :user_id',
				'params'=>array(
					':user_id'=>$user_id
				)
			);
			$pdo->update('users',$params,$where);
		} else {			
			$pdo->insert('users',$params);
		}

		redirect($HOST."/users.php");
	}
	 
}

include_once 'layout/header.php';
?>
<div class="container my-md-4">
      <div class="container-fluid">
		 <h4>Add New Employee</h4>
		 <div class="d-grid gap-2 d-md-flex justify-content-md-end">
	      	<a class="btn btn-outline-dark" href="users.php" role="button">Back</a>
      	</div>

		<form class="row g-3" method="POST" enctype="multipart/form-data" action="">
			<div class="col-md-6">
		    	<label for="fname" class="form-label">First Name</label>
		    	<input type="text" class="form-control" id="fname" name="fname" value="<?php echo !empty($fname)?$fname:'';?>">
		    	<p class="error"><?php echo !empty($error['fname'])?$error['fname']:"";?></p>
		  	</div>
		  	<div class="col-md-6">
		    	<label for="lname" class="form-label">Last Name</label>
		    	<input type="text" class="form-control" id="lname" name="lname" value="<?php echo !empty($lname)?$lname:'';?>">
		    	<p class="error"><?php echo !empty($error['lname'])?$error['lname']:"";?></p>
		  	</div>
		  	<div class="col-md-6">
		    	<label for="email" class="form-label">Email</label>
		    	<input type="email" class="form-control" id="email" name="email" value="<?php echo !empty($email)?$email:'';?>">
		    	<p class="error"><?php echo !empty($error['email'])?$error['email']:"";?></p>
		  	</div>
		  	<div class="col-md-6">
		    	<label for="phone" class="form-label">Phone Number</label>
		    	<input type="text" class="form-control" maxlength="10" id="phone" name="phone" value="<?php echo !empty($phone)?$phone:'';?>">
		    	<p class="error"><?php echo !empty($error['phone'])?$error['phone']:"";?></p>
		  	</div>
		  	<div class="col-md-6">
		    	<label for="password" class="form-label">Password</label>
		    	<input class="form-control" type="text" id="auto_password" name="auto_password" value="" aria-label="Auto Generated Password" > 
		    	<!-- <input type="password" class="form-control" id="password" name="password" value=""> -->
		    	<p class="error"><?php echo !empty($error['password'])?$error['password']:"";?></p>
		  	</div>
		   
		  	<div class="col-12">
			    <div class="form-check">
			      <input class="form-check-input" type="checkbox" id="auto_generate">
			      <label class="form-check-label" for="auto_generate">
			        Auto Generate Password
			      </label>
			    </div>
		  	</div>
		  	<div class="col-12">
		  		<input type="hidden" name="user_id" value="<?php echo $user_id?$user_id:0;?>">
		    	<button type="submit" name="adduser" class="btn btn-primary">Submit</button>
		  	</div>
		</form>
	</div>
</div>
<script type="text/javascript">
	
 
function generatePassword() {
    var length = 8,
        charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
        retVal = "";
    for (var i = 0, n = charset.length; i < length; ++i) {
        retVal += charset.charAt(Math.floor(Math.random() * n));
    }
    return retVal;
}


$(window).ready(function(){
	$('#auto_generate').off('click');
	$('#auto_generate').on('click',function(){
		var password = generatePassword();

		if($(this).is(':checked')){
			$('#auto_password').val(password);
		} else {
			$('#auto_password').val('');
		}
	});
});
</script>

<?php 
include_once 'layout/footer.php';
?>