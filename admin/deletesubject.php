<?php
include_once("../database/db_conection.php");
if($_POST['id'])
{
    $id = mysqli_real_escape_string($db_conn,$_POST['id']);

    $check_subject_query="select * from users WHERE user_subject = ".$id;    
    $updateresult = mysqli_query($db_conn, $check_subject_query);   
    if(mysqli_num_rows($updateresult)>0){
        $error_flag = true;
        echo "<div role='alert' class='alert alert-danger alert-dismissible fade in'><button aria-label='Close' data-dismiss='alert' class='close' type='button'><span aria-hidden='true'>×</span></button>this subject is already assigned to some users so you can not delete this subject.</div>";   
    } else {
        $delete = "DELETE FROM subject WHERE id=".$id;
        mysqli_query($db_conn,$delete);
        echo "<div role='alert' class='alert alert-danger alert-dismissible fade in'><button aria-label='Close' data-dismiss='alert' class='close' type='button'><span aria-hidden='true'>×</span></button>Delete Success.</div>";
    }
}
?>