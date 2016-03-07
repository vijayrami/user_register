<?php
session_start();
include_once("database/db_conection.php");
if(!$_SESSION['user'])  
{   
    header("Location: index.php");//redirect to login page to secure the welcome page without login access
}   
include_once("header.php");
?>
<body>  
<h1>Welcome</h1><br>  
<?php  
echo $_SESSION['user'];  
?>  
 
<h2><a href="logout.php?logout">Logout here</a> </h2>  
  
</body>
</html>