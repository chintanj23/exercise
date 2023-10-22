<?php 
include_once __DIR__ . '/includes/connect.php';

if(empty($_SESSION['user'])){
	redirect($HOST."/index.php");
}
 

$error=array();
if(isset($_POST['addtask'])){
 
	$start_time= makeSafe($_POST['start_time']);
	$stop_time= makeSafe($_POST['stop_time']);
	$note = makeSafe($_POST['note']);
	$description = makeSafe($_POST['description']); 

	if(empty($start_time)){
		$error['start_time']="Start Time is required";
	}
	if(empty($stop_time)){
		$error['stop_time']="Stop Time is required";
	}
	if(empty($note)){
		$error['note']="Please add Task note";
	}
	if(empty($description)){
		$error['description']="Please add Task description";
	}   

	if(empty($error['start_time']) && empty($error['stop_time'])){
		if(strtotime($start_time)>strtotime($stop_time)){
			$error['stop_time']="Invalid Stop time";
		}
	}

	//add user 
	if(empty($error)){
		$params=array(
			'user_id'=>$_SESSION['user']['id'],
			'start_time'=>DATE('Y-m-d H:i:s',strtotime($start_time)),
			'stop_time'=>DATE('Y-m-d H:i:s',strtotime($stop_time)),
			'note'=>$note,
			'description'=>$description 
		);
 		$pdo->insert('tasks',$params); 

		redirect($HOST."/tasks.php");
	}
	 
}

include_once 'layout/header.php';
?>
<div class="container my-md-4">
      <div class="container-fluid">
		 <h4>Add New Task</h4>
		 <div class="d-grid gap-2 d-md-flex justify-content-md-end">
	      	<a class="btn btn-outline-dark" href="tasks.php" role="button">Back</a>
      	</div>

		<form class="row g-3" method="POST" enctype="multipart/form-data" action="">
			<div class="col-md-6">
		    	<label for="start_time" class="form-label">Start Time</label>
		    	<input type="datetime-local" class="form-control" id="start_time" name="start_time" value="<?php echo !empty($start_time)?$start_time:'';?>">
		    	<p class="error"><?php echo !empty($error['start_time'])?$error['start_time']:"";?></p>
		  	</div>
		  	<div class="col-md-6">
		    	<label for="stop_time" class="form-label">Stop Time</label>
		    	<input type="datetime-local" class="form-control" id="stop_time" name="stop_time" value="<?php echo !empty($stop_time)?$stop_time:'';?>">
		    	<p class="error"><?php echo !empty($error['stop_time'])?$error['stop_time']:"";?></p>
		  	</div>
		  	<div class="col-md-12">
		    	<label for="note" class="form-label">Note</label>
		    	<textarea name="note" id="note" class="form-control"><?php echo !empty($note)?$note:'';?></textarea> 
		    	<p class="error"><?php echo !empty($error['note'])?$error['note']:"";?></p>
		  	</div>
		  	<div class="col-md-12">
		    	<label for="description" class="form-label">Description</label>
		    	<textarea name="description" id="description" class="form-control"><?php echo !empty($description)?$description:'';?></textarea> 
		    	<p class="error"><?php echo !empty($error['description'])?$error['description']:"";?></p>
		  	</div>
 
		  	<div class="col-12"> 
		    	<button type="submit" name="addtask" class="btn btn-primary">Submit</button>
		  	</div>
		</form>
	</div>
</div> 

<?php 
include_once 'layout/footer.php';
?>