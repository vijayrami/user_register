<?php
session_start();
if(isset($_SESSION['user'])!="")
{
 header("Location: home.php");
}
include_once("database/db_conection.php");
// define variables and set to empty values
$error_flag = false;
$uploadOk = 1;
$target_dir = "uploads/";
$user_name = $user_pass = $user_email = $user_gender = "";  
if(isset($_POST['register'])){
	$user_name=mysqli_real_escape_string($db_conn,$_POST['name']);//here getting result from the post array after submitting the form. 
	$user_pass_nonmd5=mysqli_real_escape_string($db_conn,$_POST['pass']);
    $user_pass=md5(mysqli_real_escape_string($db_conn,$_POST['pass']));//same  
    $user_email=mysqli_real_escape_string($db_conn,$_POST['email']);//same 
    $user_subject=mysqli_real_escape_string($db_conn,$_POST['selectsubject']);//same 
    
    $user_desc = addslashes($_POST['description']);
    $final_desc = stripslashes($_POST['description']);
     
    if (empty($_POST["optgender"])) {
     	$error_flag = true;
    	echo "<div role='alert' class='alert alert-warning alert-dismissible fade in'><strong>Gender</strong> is required</div>";
   	} else {
     	$user_gender = mysqli_real_escape_string($db_conn,$_POST["optgender"]);
   	}
    //here query check weather if user already registered so can't register again.   
    $check_email_query="select * from users WHERE user_email='$user_email'"; 
    $result = mysqli_query($db_conn, $check_email_query);   
    if(mysqli_num_rows($result)>0){
    	$error_flag = true;
    	echo "<div role='alert' class='alert alert-warning alert-dismissible fade in'>Email <strong>$user_email</strong> is already exist in our database, Please try another one!</div>";		
	}
	if(is_uploaded_file($_FILES['userfile']['tmp_name'])){			
		
		$target_file = $target_dir . basename($_FILES["userfile"]["name"]);
		
		// Check if image file is a actual image or fake image
		$check = getimagesize($_FILES["userfile"]["tmp_name"]);
		if($check == false) {
        	echo "<div role='alert' class='alert alert-success alert-dismissible fade in'> <strong>File is not an image.</strong></div>";
	        $uploadOk = 0;
	    }
	    // Check file size
		if ($_FILES["userfile"]["size"] > 5242888) {
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
		$finalimagename = $target_dir.$imageFilename.'_'.time().'.'.$imageFileType;
		$queryimage = $imageFilename.'_'.time().'.'.$imageFileType;
			
	} else {
		$finalimagename = $target_dir.'Dummy.jpg';
		$queryimage = 'Dummy.jpg';
	}
	//insert the user into the database.
	if (($error_flag == false) && ($uploadOk == 1)) {	    
	$_SESSION['user'] = $user_name;
	move_uploaded_file($_FILES["userfile"]["tmp_name"], $finalimagename);
	$insert_user="insert into users (user_name,user_pass,user_email,image,user_gender,user_subject,user_description) VALUE ('$user_name','$user_pass','$user_email','$queryimage','$user_gender','$user_subject','$user_desc')";
	if(mysqli_query($db_conn,$insert_user))  
    {  
        header("Location: home.php"); 
    } 
    } 
    
} 

include_once("header.php");
?>
  <body>		
    <div class="container">
    <div class="row">
        <!---<div class="col-md-4 col-md-offset-4">---> <!--comment this if you use ckeditor---> 
            <div class="login-panel panel panel-success">  
                <div class="panel-heading">  
                    <h3 class="panel-title text-center">Registration</h3>  
                </div>  
                <div class="panel-body">  
                    <form enctype="multipart/form-data" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">  
                        <fieldset>  
                            <div class="form-group"> 
                            	<label for="registerusername11">User Name</label> 
                                <input type="text" placeholder="Username" name="name" value="<?php echo $user_name; ?>" class="form-control" required autofocus>  
                            </div>  
  
                            <div class="form-group">  
                            	<label for="registeremail11">E-mail</label> 
                                <input type="email" placeholder="E-mail" name="email" value="<?php echo $user_email;?>" class="form-control" autofocus required>             
                            </div>  
                            <div class="form-group">
						    	<label for="exampleInputFile">Profile Image</label>
						    	<input type="file" name="userfile" id="exampleInputFile">		
						  	</div>
						  	<div class="form-group">
						    	<label for="exampleInputgender">Gender:</label>
						    	<label class="radio-inline"><input type="radio" name="optgender" value="Male" <?php if (isset($user_gender) && $user_gender=="Male") echo "checked";?> required autofocus>Male</label>
								<label class="radio-inline"><input type="radio" name="optgender" value="Female" <?php if (isset($user_gender) && $user_gender=="Female") echo "checked";?>>Female</label>
						  	</div>
						  	<div class="form-group">
                                <label for="exampleInputgender">Select Subject:</label>
                                <?php
                                $select_subject_query="Select * from subject";
                                $select_subject_query_run =mysqli_query($db_conn,$select_subject_query);
                                echo "<select name='selectsubject' required>";
                                echo "<option value=''>None</option>";
                                while ($select_subject_query_array = mysqli_fetch_array($select_subject_query_run) )
                                { ?>
                                   <option value="<?php echo htmlspecialchars($select_subject_query_array['id']);?>" <?php if (!empty($user_subject) && $user_subject==htmlspecialchars($select_subject_query_array["id"])) echo "selected";?>><?php echo htmlspecialchars($select_subject_query_array["subject_name"]);?></option>
                                <?php }
                                echo "</select>";
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputdes">Description</label>                                
                                <textarea class="ckeditor" rows="5" cols="100" name="description" id="register_editor"><?php echo (!empty($final_desc))?$final_desc:"";?></textarea>
                            </div>                            
                            <div class="form-group"> 
                            	<label for="registerpass11">Password</label>  
                                <input class="form-control" placeholder="Password" name="pass" type="password" value="" required>  
                            </div>  
  
  
                            <input class="btn btn-lg btn-success btn-block" type="submit" value="register" name="register" >  
  
                        </fieldset>  
                    </form>  
                    <center><b>Already registered ?</b> <br><a href="index.php">Login here</a></center><!--for centered text-->  
                </div>  
            </div>  
        <!--</div>--> <!--comment this if you use ckeditor--->  
    </div>  
</div> 

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="lib/js/bootstrap.min.js"></script>
    
  </body>
</html>
