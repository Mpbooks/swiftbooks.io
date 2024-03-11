<?php

//print_r($_REQUEST);
   if(isset($_POST['submit']) && !empty($_POST['email'])  && !empty($_POST['password'])){
    
    include_once('config.php');
    $email =$_POST['email'];
    $senha =$_POST['password'];

   // print_r('email:' . $email);
    //print_r('password:' . $senha);
    $sql= "SELECT *  FROM  mensagens WHERE email= '$email' and senha = '$senha'";

    $result = $conn->query($sql);

   // print_r($result);

   if(mysqli_num_rows($result) < 1){
       header('Location:  login.html');
}else{
     header('Location: loja.php');
}
   }
    else
    {
       header('Location: login.html');
    } 
 ?>