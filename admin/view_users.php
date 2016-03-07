<?php
session_start();
include_once("../database/db_conection.php");
if(!$_SESSION['admin_user'])  
{  
  header("Location: index.php"); 
}  
include_once("../header.php");

if(isset($_POST['saveuserbtn']))//this will tell us what to do if some data has been post through form with button.  
{  
	$error_flag = false;
	$uploadOk = 1;
	$target_dir = '../'."uploads/";
    $update_id=$_POST['updateuserid']; 
    $update_image=$_POST['updateuserimage']; 
    $update_username = mysqli_real_escape_string($db_conn,$_POST['editusername']);
    $update_useremail = mysqli_real_escape_string($db_conn,$_POST['edituseremail']);
    $update_userpass = md5(mysqli_real_escape_string($db_conn,$_POST['edituserpass']));
    $update_usersubject = mysqli_real_escape_string($db_conn,$_POST['selectadminsubject']);
    $update_userdesc = addslashes($_POST['edit_admin_desc']);
    $update_final_userdesc = stripslashes($_POST['edit_admin_desc']);
    
    if (empty($_POST["opteditgender"])) {
     	$error_flag = true;
    	echo "<div role='alert' class='alert alert-warning alert-dismissible fade in'><strong>Gender</strong> is required</div>";
   	} else {
     	$update_usergender = mysqli_real_escape_string($db_conn,$_POST["opteditgender"]);
   	}
   	
    $check_update_email_query="select * from users WHERE user_email='$update_useremail' AND ID !='$update_id' ";    
    $updateresult = mysqli_query($db_conn, $check_update_email_query);   
    if(mysqli_num_rows($updateresult)>0){
    	$error_flag = true;
    	echo "<div role='alert' class='alert alert-warning alert-dismissible fade in'>Email <strong>$update_useremail</strong> is already exist in our database, Please try another one!</div>";		
	}
	if(is_uploaded_file($_FILES['edituserimage']['tmp_name'])){			
		
		$target_file = $target_dir . basename($_FILES["edituserimage"]["name"]);
		
		// Check if image file is a actual image or fake image
		$check = getimagesize($_FILES["edituserimage"]["tmp_name"]);
		if($check == false) {
        	echo "<div role='alert' class='alert alert-success alert-dismissible fade in'> <strong>File is not an image.</strong></div>";
	        $uploadOk = 0;
	    }
	    // Check file size
		if ($_FILES["edituserimage"]["size"] > 5242888) {
		    echo "<div role='alert' class='alert alert-success alert-dismissible fade in'> <strong>Sorry, your file is too large.</strong></div>";
		    $uploadOk = 0;
		}
		// Allow certain file formats
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);		
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
		    echo "<div role='alert' class='alert alert-success alert-dismissible fade in'> <strong>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</strong></div>";
		    $uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
    		echo "<div role='alert' class='alert alert-success alert-dismissible fade in'> <strong>Sorry, your file was not uploaded.</strong></div>";
		}
		$imageFilename = pathinfo($target_file,PATHINFO_FILENAME);	
		$savefinalimagename = $target_dir.$imageFilename.'_'.time().'.'.$imageFileType;
		$savequeryimage = $imageFilename.'_'.time().'.'.$imageFileType;
			
	} else {
		$uploadOk = 2;
	}
	
	if (($error_flag == false)&&($uploadOk == 1)){
	move_uploaded_file($_FILES["edituserimage"]["tmp_name"], $savefinalimagename);
	if ($update_image != 'Dummy.jpg'){
		unlink("$target_dir$update_image");
	}
    $update_query="UPDATE users SET user_name='$update_username',user_pass='$update_userpass',user_email='$update_useremail',image='$savequeryimage',user_gender='$update_usergender',user_subject='$update_usersubject', user_description='$update_userdesc' where id='$update_id'";  
  	
    $run_updatequery=mysqli_query($db_conn,$update_query); 
    if($run_updatequery)  
	{  
	   echo "<div role='alert' class='alert alert-success alert-dismissible fade in'> <strong>User ID $update_id </strong> has been Updated successfully.</div>";
	}
	}  
	if (($error_flag == false) && ($uploadOk == 2)){	
    $update_query="UPDATE users SET user_name='$update_username',user_pass='$update_userpass',user_email='$update_useremail',image='$update_image',user_gender='$update_usergender',user_subject='$update_usersubject', user_description='$update_userdesc' where id='$update_id'";  
    
    $run_updatequery=mysqli_query($db_conn,$update_query); 
    if($run_updatequery)  
	{  
	   echo "<div role='alert' class='alert alert-success alert-dismissible fade in'> <strong>User ID $update_id </strong> has been Updated successfully.</div>";
	}
	}
}
if(isset($_POST['adduserbtn']))//this will tell us what to do if some data has been post through form with button.  
{  
	$error_flag = false;
	$uploadOk = 1;
	$target_dir = '../'."uploads/";
	
    $add_username = mysqli_real_escape_string($db_conn,$_POST['addusername']);
    $add_useremail = mysqli_real_escape_string($db_conn,$_POST['adduseremail']);
    $add_userpass = md5(mysqli_real_escape_string($db_conn,$_POST['adduserpass']));
    $add_usersubject = mysqli_real_escape_string($db_conn,$_POST['selectadminsubject']);
    
    $add_userdesc=addslashes($_POST['add_admin_desc']);
    $final_adduserdesc = stripslashes($_POST['add_admin_desc']);
    
    if (empty($_POST["optaddgender"])) {
     	$error_flag = true;
    	echo "<div role='alert' class='alert alert-warning alert-dismissible fade in'><strong>Gender</strong> is required</div>";
   	} else {
     	$add_usergender = mysqli_real_escape_string($db_conn,$_POST["optaddgender"]);
   	}
   	
    $check_add_email_query="select * from users WHERE user_email='$add_useremail'"; 
    $addresult = mysqli_query($db_conn, $check_add_email_query);   
    if(mysqli_num_rows($addresult)>0){
    	$error_flag = true;
    	echo "<div role='alert' class='alert alert-warning alert-dismissible fade in'>Email <strong>$add_useremail</strong> is already exist in our database, Please try another one!</div>";		
	}
	if(is_uploaded_file($_FILES['adduserfile']['tmp_name'])){			
		
		$target_file = $target_dir . basename($_FILES["adduserfile"]["name"]);
		
		// Check if image file is a actual image or fake image
		$check = getimagesize($_FILES["adduserfile"]["tmp_name"]);
		if($check == false) {
        	echo "<div role='alert' class='alert alert-success alert-dismissible fade in'> <strong>File is not an image.</strong></div>";
	        $uploadOk = 0;
	    }
	    // Check file size
		if ($_FILES["adduserfile"]["size"] > 5242888) {
		    echo "<div role='alert' class='alert alert-success alert-dismissible fade in'> <strong>Sorry, your file is too large.</strong></div>";
		    $uploadOk = 0;
		}
		// Allow certain file formats
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);		
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
		    echo "<div role='alert' class='alert alert-success alert-dismissible fade in'> <strong>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</strong></div>";
		    $uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
    		echo "<div role='alert' class='alert alert-success alert-dismissible fade in'> <strong>Sorry, your file was not uploaded.</strong></div>";
		}
		$imageFilename = pathinfo($target_file,PATHINFO_FILENAME);	
		$addfinalimagename = $target_dir.$imageFilename.'_'.time().'.'.$imageFileType;
		$addqueryimage = $imageFilename.'_'.time().'.'.$imageFileType;
			
	} else {
		$addfinalimagename = $target_dir.'Dummy.jpg';
		$addqueryimage = 'Dummy.jpg';
	}
	if (($error_flag == false) && ($uploadOk == 1)){
	move_uploaded_file($_FILES["adduserfile"]["tmp_name"], $addfinalimagename);
    $add_query="insert into users (user_name,user_pass,user_email,image,user_gender,user_subject,user_description) VALUE ('$add_username','$add_userpass','$add_useremail','$addqueryimage','$add_usergender','$add_usersubject','$add_userdesc')";  
  	
    $run_addquery=mysqli_query($db_conn,$add_query); 
    if($run_addquery)  
	{  
	   echo "<div role='alert' class='alert alert-success alert-dismissible fade in'>User Emal <strong>$add_useremail </strong> has been added successfully.</div>";
	}
	}  
}
?>
<body>
<div class="container">
	<div class="row">
	<div class="table-scrol"> 
		<div class="row">
		<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
	    		<h1 align="center">All the Users</h1> 
	    </div>  
	    </div>
	    <p></p>
	    <div class="row">
	    <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
	  	<form action='<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>' method="post">
		<input type="submit" class="btn btn-success" name="adduser" value="Add User">
		</form>
		</div>  
		<div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
		<a href="view_subjects.php" class="btn btn-success" role="button">Subjects</a>
		</div>	
		
		<div class="pull-right offset-0">		
		<!-- Small log out modal start -->
		<button class="btn btn-danger" data-toggle="modal" data-target=".bs-example-modal-sm"><i class="fa fa-sign-out"></i>Logout</button>

		<div class="modal bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
		  <div class="modal-dialog modal-sm">
			<div class="modal-content">
			  <div class="modal-header"><h4>Logout <i class="fa fa-lock"></i></h4></div>
			  <div class="modal-body"><i class="fa fa-question-circle"></i> Are you sure you want to log-off?</div>
			  <div class="modal-footer"><a href="logout.php?logout" class="btn btn-danger btn-block">Logout</a></div>
			</div>
		  </div>
		</div>
		<!-- Small log out modal ends -->
		</div>
		</div>
		<p></p>
		<?php
		if(isset($_POST['adduser']))//this will tell us what to do if some data has been post through form with button.  
		{  ?>
		    <div class="container">
		    	<div class="row">
			    <h2>Add Users</h2>
			    
			<form action='<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>' method="post" enctype="multipart/form-data">
		      
			  <div class="form-group">
			    <label for="addusername11">User Name</label>
			    <input type="text" class="form-control" name="addusername" value="" placeholder="Username" required  autofocus>
			  </div>
			  <div class="form-group">
			    <label for="adduseremail11">Email address</label>
			    <input type="email" class="form-control" name="adduseremail" value="" placeholder="E-mail" autofocus required>
			  </div>
			  <div class="form-group">
		    	<label for="adduserInputFile">Profile Image</label>
		    	<input type="file" name="adduserfile" id="adduserInputFile">			
		  	  </div>
		  	  <div class="form-group">
		    	<label for="addusergender">Gender:</label>
		    	<label class="radio-inline"><input type="radio" name="optaddgender" value="Male" <?php if (isset($add_usergender) && $add_usergender=="Male") echo "checked";?> required autofocus>Male</label>
				<label class="radio-inline"><input type="radio" name="optaddgender" value="Female" <?php if (isset($add_usergender) && $add_usergender=="Female") echo "checked";?>>Female</label>
		  	  </div>
		  	  <div class="form-group">
                <label for="exampleInputsubject">Select Subject:</label>
                <?php
                $select_subject_query="Select * from subject";
                $select_subject_query_run =mysqli_query($db_conn,$select_subject_query);
                echo "<select name='selectadminsubject' required autofocus>";
                echo "<option value=''>None</option>";
                while ($select_subject_query_array = mysqli_fetch_array($select_subject_query_run) )
                {
                   echo "<option value=".htmlspecialchars($select_subject_query_array['id'])." >".htmlspecialchars($select_subject_query_array["subject_name"])."</option>";
                }
                echo "</select>";
                ?>
            </div>
            <div class="form-group">
                <label for="adduserdesc11">Description</label>
                <textarea class="ckeditor" rows="5" cols="100" id="register_editor" name="add_admin_desc"><?php echo (!empty($final_adduserdesc))?$final_adduserdesc:"";?></textarea>
              </div>
			  <div class="form-group">
			    <label for="adduserpassl11">Password</label>
			    <input type="password" class="form-control" name="adduserpass" placeholder="Password" required>
			  </div>  
			  <input class="btn btn-lg btn-success btn-block" type="submit" value="Add" name="adduserbtn" >
			</form>
			</div>
		</div>
		<?php } ?>
		<p></p>
		<div class="row">
		<div class="table-responsive"><!--this is used for responsive display in mobile and other devices-->  
	  
	  
	    <table id="example" class="table table-bordered table-hover table-striped" style="table-layout: fixed">  
	        <thead>
	        <tr>  
	  
	            <th class="col-md-1 col-sm-1">User Id</th>  
	            <th class="col-md-1 col-sm-1">User Name</th>  
	            <th class="col-md-2 col-sm-2">User E-mail</th> 
	            <th class="col-md-1 col-sm-1">Gender</th> 
	            <th class="col-md-2 col-sm-2">Image</th>  
	            <th class="col-md-2 col-sm-2">Subject</th>  
	            <th class="col-md-2 col-sm-2">Description</th>  
	            <th class="col-md-1">Edit User</th> 
	            <th class="col-md-1">Delete User</th>  
	        </tr>  
	        </thead> 
	        <tfoot>
            <tr>  
      
                <th class="col-md-1 col-sm-1">User Id</th>  
                <th class="col-md-1 col-sm-1">User Name</th>  
                <th class="col-md-2 col-sm-2">User E-mail</th> 
                <th class="col-md-1 col-sm-1">Gender</th> 
                <th class="col-md-2 col-sm-2">Image</th> 
                <th class="col-md-2 col-sm-2">Subject</th>  
                <th class="col-md-2 col-sm-2">Description</th>   
                <th class="col-md-1">Edit User</th> 
                <th class="col-md-1">Delete User</th>  
            </tr>  
            </tfoot>  
	        <tbody>
	        <?php  
	        $num_rec_per_page=10;
	        if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
	        $start_from = ($page-1) * $num_rec_per_page; 
	        $view_users_query="select * from users LIMIT $start_from, $num_rec_per_page";//select query for viewing users. 
	        $run=mysqli_query($db_conn,$view_users_query);//here run the sql query.  
	  		if(mysqli_num_rows($run)>0){
	        while($row=mysqli_fetch_array($run))//while look to fetch the result and store in a array $row.  
	        {  
	            $user_id=$row[0];  
	            $user_name=$row[1];  
	            $user_email=$row[3];
	            $user_gender=$row[5];  
	            $user_image=$row[4];   
	            $user_subject=$row[6];   
                $user_desc=stripslashes($row[7]);   

                $view_users_subject="select * from subject WHERE id = '$user_subject'";//select query for viewing users.  
                $srun = mysqli_query($db_conn,$view_users_subject);//here run the sql query.  

                while($srow=mysqli_fetch_assoc($srun))//while look to fetch the result and store in a array $row.  
                {  
                    $user_subject = $srow['subject_name'];
                }    
            ?>  
	  
	        <tr>  
	            <!--here showing results in the table -->  
	            <td class="col-md-1 col-sm-1"><?php echo $user_id;  ?></td>  
	            <td class="col-md-1 col-sm-1"><?php echo $user_name;  ?></td>  
	            <td class="col-md-2 col-sm-2"><?php echo $user_email;  ?></td>  
	            <td class="col-md-1 col-sm-1"><?php echo $user_gender;  ?></td>  
	            <td class="col-md-2 col-sm-2"><img alt="<?php echo $user_name;?>" height="100px" width="100px" src="../uploads/<?php echo $user_image;?>"></td>  
	            <td class="col-md-2 col-sm-2">
                    <?php echo $user_subject;  ?>
                </td>  
                <td class="col-md-2 col-sm-2"><?php echo $user_desc;  ?></td>  
	            <td class="col-md-1">
	            	<form action='<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>' method="post">
      				<input type="hidden" name="edituserid" value="<?php echo $user_id ?>">
      				<input type="submit" class="btn btn-success" name="edituser" value="Edit">
      				</form>
	            </td> <!--btn btn-danger is a bootstrap button to show danger-->	             
	            <td class="col-md-1">
	            	
      				<!--<a class="btn btn-danger delete" href="#" id="<?php echo $user_id; ?>">Delete</a>-->
      				<input type="submit" class="btn btn-danger delete" id="<?php echo $user_id; ?>" name="deleteuser" value="Delete">
	            </td> <!--btn btn-danger is a bootstrap button to show danger-->  
	            
	        </tr>  
	  
	        <?php }
	        } else {
				echo "<tr><td colspan='9'><h3 class='text-center'>No Records Found</h3></tr></td>";
			}
	        
	        ?>  
	        </tbody>
	    </table> 
	    <?php 
		$paginationsql = "SELECT * FROM users"; 
		$pagination_result = mysqli_query($db_conn,$paginationsql); //run the query
		$total_records = mysqli_num_rows($pagination_result);  //count number of records
		$total_pages = ceil($total_records / $num_rec_per_page); 
		
		echo "<a href='view_users.php?page=1'>".'|<'."</a> "; // Goto 1st page  

		for ($i=1; $i<=$total_pages; $i++) { 
		    echo "<a href='view_users.php?page=".$i."'>".$i."</a> "; 
		}; 
		echo "<a href='view_users.php?page=$total_pages'>".'>|'."</a> "; // Goto last page
		?> 
	    </div>  
	    </div>
	</div>
	</div>
</div>
<?php
if(isset($_POST['edituser']))//this will tell us what to do if some data has been post through form with button.  
{  
    $edit_id=$_POST['edituserid']; 
    $get_user_query="SELECT * FROM users where id='$edit_id'";
    $run_getuserquery=mysqli_query($db_conn,$get_user_query);
    $user=mysqli_fetch_row($run_getuserquery); 
    ?>
    <div class="container">
    	<div class="row">
	    <h2>Edit Users</h2>
		<form action='<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>' method="post" enctype="multipart/form-data">
	  <div class="form-group">
	    <label for="editusername11">User Name</label>
	    <input type="text" class="form-control" name="editusername" value="<?php echo $user[1];?>" placeholder="Username" required  autofocus>
	  </div>
	  <div class="form-group">
	    <label for="edituseremail11">Email address</label>
	    <input type="email" class="form-control" name="edituseremail" value="<?php echo $user[3];?>" placeholder="E-mail" autofocus required>
	  </div>
	  <div class="form-group">
    		<label for="editusergender11">Gender:</label>
    		<label class="radio-inline"><input type="radio" name="opteditgender" value="Male" <?php if (!empty($user[5]) && $user[5]=="Male") echo "checked";?>>Male</label>
			<label class="radio-inline"><input type="radio" name="opteditgender" value="Female" <?php if (!empty($user[5]) && $user[5]=="Female") echo "checked";?>>Female</label>
  	  </div>
	  <div class="form-group">
	    <label for="edituserimage11">User Image</label>
	    <input type="file" name="edituserimage">
	    <img alt="<?php echo $user[1];?>" height="100px" width="100px" src="../uploads/<?php echo $user[4];?>">
	  </div>
	  <div class="form-group">
            <label for="exampleInputeditsubject">Select Subject:</label>
            <?php
            $select_subject_query="Select * from subject";
            $select_subject_query_run =mysqli_query($db_conn,$select_subject_query);
            echo "<select name='selectadminsubject' required>";
            echo "<option value=''>None</option>";
            while ($select_subject_query_array = mysqli_fetch_array($select_subject_query_run) )
            { ?>
               <option value="<?php echo htmlspecialchars($select_subject_query_array['id']);?>" <?php if (!empty($user[6]) && $user[6]==htmlspecialchars($select_subject_query_array["id"])) echo "selected";?>><?php echo htmlspecialchars($select_subject_query_array['subject_name']); ?></option>
            <?php }
            echo "</select>";
            ?>
      </div>
      <div class="form-group">
                <label for="edituserdesc11">Description</label>
                <textarea class="ckeditor" rows="5" cols="100" id="register_editor" name="edit_admin_desc"><?php echo stripslashes($user[7]);?></textarea>
      </div>
	  <div class="form-group">
	    <label for="edituserpassl11">Password</label>
	    <input type="password" class="form-control" name="edituserpass" placeholder="Password" required>
	  </div>  
	  <input type="hidden" name="updateuserid" value="<?php echo $user[0];?>">
	  <input type="hidden" name="updateuserimage" value="<?php echo $user[4];?>">
	  <input class="btn btn-lg btn-success btn-block" type="submit" value="Save" name="saveuserbtn" >
	</form>
	</div>
</div>
<?php } ?>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->    
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="lib/js/bootstrap.min.js"></script>
    
    <script type="text/javascript">
	$(function() {
	$(".btn.btn-danger.delete").click(function(){
	var element = $(this);
	var del_id = element.attr("id");
	var info = 'id=' + del_id;
	if(confirm("Are you sure you want to delete this?"))
	{
	 $.ajax({
	   type: "POST",
	   url: "delete.php",
	   data: info,
	   success: function(){
	   	
	 }
	});
      //$(this).parents("tr").animate({backgroundColor: "#003" }, "slow").animate({opacity: "hide"}, "slow").remove();
     // $(this).parents("tr").remove(); 
       	$( this ).parents("tr").hide( 1200, function() {
    	$( this ).remove();
  		});
	 }
	return false;
	});
	});
</script>


     
  </body>
</html>