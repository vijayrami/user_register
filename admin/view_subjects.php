<?php
session_start();
include_once("../database/db_conection.php");
if(!$_SESSION['admin_user'])  
{  
  header("Location: index.php"); 
}  
include_once("../header.php");
if(isset($_POST['addusersubject']))//this will tell us what to do if some data has been post through form with button.  
{
    $error_flag = false;
    $add_usersubject = mysqli_real_escape_string($db_conn,$_POST['usersubject']);
    
    $check_add_subject_query="select * from subject WHERE subject_name='$add_usersubject'"; 
    $addresultsubject = mysqli_query($db_conn, $check_add_subject_query);   
    if(mysqli_num_rows($addresultsubject)>0){
        $error_flag = true;
        echo "<div role='alert' class='alert alert-warning alert-dismissible fade in'>Subject <strong>$add_usersubject</strong> is already exist in our database, Please try another one!</div>";       
    }
    if ($error_flag == false){
        $add_subject_query="insert into subject(subject_name) VALUE ('$add_usersubject')";  
        
        $run_addsubjectquery=mysqli_query($db_conn,$add_subject_query); 
        if($run_addsubjectquery)  
        {  
           echo "<div role='alert' class='alert alert-success alert-dismissible fade in'>Subject <strong>$add_usersubject</strong> has been added successfully.</div>";
        }
    }
}
if(isset($_POST['saveusersubjectbtn']))
{
   $error_flag = false;
   $update_subjectid=$_POST['updateusersubjectid']; 
   $update_subjectname = mysqli_real_escape_string($db_conn,$_POST['editusersubject']); 
   
    $check_update_subject_query="select * from subject WHERE subject_name='$update_subjectname' AND id !='$update_subjectid' ";    
    $updatesubjectresult = mysqli_query($db_conn, $check_update_subject_query);   
    if(mysqli_num_rows($updatesubjectresult)>0){
        $error_flag = true;
        echo "<div role='alert' class='alert alert-warning alert-dismissible fade in'>Subject <strong>$update_subjectname</strong> is already exist in our database, Please try another one!</div>";        
    }
    if ($error_flag == false){
    $update_subjectquery="UPDATE subject SET subject_name='$update_subjectname' where id='$update_subjectid'";  
    
    $run_updatesubjectquery=mysqli_query($db_conn,$update_subjectquery); 
    if($run_updatesubjectquery)  
    {  
       echo "<div role='alert' class='alert alert-success alert-dismissible fade in'> <strong>Subject ID $update_subjectid </strong> has been Updated successfully.</div>";
    }
    }  
}
?>
<body>
<div id="SomeDiv"></div>
    <div class="container">
        <div class="row">
            <div class="table-scrol">  
                <h1 align="center">All the Subjects</h1>
                <form action='<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>' method="post">
                <input type="submit" class="btn btn-success btn-lg" name="addsubject" value="Add">
                </form> 
                <?php
                if(isset($_POST['addsubject']))//this will tell us what to do if some data has been post through form with button.  
                {  ?>
                    <div class="container">
                        <div class="row">
                        <h2>Add Subject</h2>
                        <form action='<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>' method="post" enctype="multipart/form-data">
                          <div class="form-group">
                            <label for="addusersubject11">Subject Name</label>
                            <input type="text" class="form-control" name="usersubject" value="" placeholder="Subject" required  autofocus>
                          </div>
                          <input class="btn btn-lg btn-success btn-block" type="submit" value="Add" name="addusersubject" >
                    </form>
                    </div>
                </div>
                <?php } ?>
				<p></p>
                <div class="table-responsive">
                    <table id="examplesubject" class="table table-bordered table-hover table-striped" style="table-layout: fixed">
                        <thead>
                        <tr>  
                            <th class="col-md-1">Subject Id</th>  
                            <th class="col-md-2">Subject Name</th>   
                            <th class="col-md-1">Edit Subject</th> 
                            <th class="col-md-1">Delete Subject</th>  
                        </tr>  
                        </thead> 
                        <tfoot>
                        <tr>  
                            <th class="col-md-1">Subject Id</th>  
                            <th class="col-md-2">Subject Name</th>  
                            <th class="col-md-1">Edit Subject</th> 
                            <th class="col-md-1">Delete Subject</th>
                        </tr>  
                        </tfoot>  
                        <tbody>
                            <?php
                            $view_subjects_query="select * from subject";//select query for viewing users.  
                            $run=mysqli_query($db_conn,$view_subjects_query);//here run the sql query.  
                            if(mysqli_num_rows($run)>0){
                            while($row=mysqli_fetch_array($run))//while look to fetch the result and store in a array $row.  
                            {  
                                $subject_id=$row[0];  
                                $subject_name=$row[1];  
                            ?>
                            <tr id="<?php echo $subject_id;  ?>">  
                                <!--here showing results in the table -->  
                                <td class="col-md-1"><?php echo $subject_id;  ?></td>  
                                <td class="col-md-2"><?php echo $subject_name;  ?></td> 
                            
                                <td class="col-md-1">
                                    <form action='<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>' method="post">
                                    <input type="hidden" name="editsubjectid" value="<?php echo $subject_id; ?>">
                                    <input type="submit" class="btn btn-success" name="editsubject" value="Edit">
                                    </form>
                                </td> <!--btn btn-danger is a bootstrap button to show danger-->                 
                                <td class="col-md-1">                                    
                                    <!--<a class="btn btn-danger delete" href="#" id="<?php echo $user_id; ?>">Delete</a>-->
                                    <input type="submit" class="btn btn-danger delete" id="<?php echo $subject_id; ?>" name="deletesubject" value="Delete">
                                </td> <!--btn btn-danger is a bootstrap button to show danger--> 
                                
                            </tr>  
                            <?php }
							}else {
								echo "<tr><td colspan='4'><h3 class='text-center'>No Records Found</h3></tr></td>";
							}?>
                        </tbody>
                    </table>
                </div>
            </div>            
        </div>
    </div>
    
<?php
if(isset($_POST['editsubject']))//this will tell us what to do if some data has been post through form with button.  
{  
    $edit_subjectid=$_POST['editsubjectid']; 
    $get_user_subjectquery="SELECT * FROM subject where id='$edit_subjectid'";
    $run_getusersubjectquery=mysqli_query($db_conn,$get_user_subjectquery);
    $usersubject=mysqli_fetch_row($run_getusersubjectquery); 
    ?>
    <div class="container">
        <div class="row">
        <h2>Edit Subject</h2>
        <form action='<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>' method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="updateusersubject11">Subject Name</label>
                <input type="text" class="form-control" name="editusersubject" value="<?php echo $usersubject[1];?>" placeholder="Subject" required  autofocus>
            </div>
            <input type="hidden" name="updateusersubjectid" value="<?php echo $usersubject[0];?>">
            <input class="btn btn-lg btn-success btn-block" type="submit" value="Save" name="saveusersubjectbtn" >
        </form>
    </div>
</div>
<?php } ?>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="lib/js/bootstrap.min.js"></script>

    <script type="text/javascript">
        $(function() 
        {
            $(".btn.btn-danger.delete").click(function()
            {
                var element = $(this);
                var del_id = element.attr("id");
                var info = 'id=' + del_id;
                if(confirm("Are you sure you want to delete this?"))
                {
                     $.ajax({
                       type: "POST",
                       url: "deletesubject.php",
                       data: info,
                       success: function(response){
                       		$("#SomeDiv").html(response);     
                            /*if(response == "delete_success") {
                                $('#'+del_id).animate({backgroundColor: "#003" }, "slow").animate({opacity: "hide"}, "slow");
                            } else if(response != "delete_success") {
                                $('.container:first').before(response);
                            }*/
                        }
                    });    
                }
                return false;
            });
        });
    </script>

  </body>
</html>

