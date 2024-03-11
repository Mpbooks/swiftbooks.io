<?php
//pegando os dados que estão vindo do formulario

$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];
$number = $_POST['number'];
$password = $_POST['password'];
$confirmpassword = $_POST['confirmpassword'];
$data_atual = date('d/m/Y');
$hora_atual = date('H:i:s');

$server = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'cadastro';

//CONEXÃO COM NOSSO BANCO DE DADOS
$conn = new mysqli($server , $usuario,$senha, $banco);

//VERIFICAR CONEXÃO
if($conn->connect_error){
    die("Falha  ao se  comunicar aos bancos  de  dados: ".$conn->connect_error);
}

$smtp = $conn->prepare("INSERT INTO mensagens (primeironome,segundonome,email,number,senha,confirmapassword,data,hora) VALUES (?,?,?,?,?,?,?,?)");

$smtp->bind_param("ssssssss", $firstname,$lastname,$email,$number,$password,$confirmpassword,$data_atual,$hora_atual);

if($smtp->execute()){
  header('Location: loja.php');
  

}else{
    echo "Erro de Cadastro: ".$smtp->error;
}

$smtp->close();
$conn->close();

?>


