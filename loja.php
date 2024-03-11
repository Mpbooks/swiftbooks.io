<?php
ob_start();
require('./sheep_core/config.php');
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="loja.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Loja-Swiftbooks</title>
   
</head>
<body>



   <header class="header">
    <h1>Swiftbooks</h1>
        <li><a class="lik" href="pesquisa.html">Pesquisar</a></li>
        <li><a class="lik" href="painel/index.php">Meus Produtos</a></li>
        <li><a class="lik" href="#">Contatos</a></li>
        </header>




<div class="container">

<div class="linha-produtos">

<?php
                              $ler = new Ler();
                              $ler->Leitura('produtos', "ORDER BY data DESC");
                              IF($ler->getResultado()){
                              foreach($ler->getResultado() as $produto){
                             $produto = (object) $produto;
                                  
                            ?>


<form action="filtros/criar.php" method="post">
<div class="corpoProduto">
    <div class="imgProduto">
        <img src="<?=HOME?>/uploads/<?= $produto->capa?>" alt="<?=$produto->nome?>" class="produtoMiniatura">
    </div>
   <div class="titulo">
    <p><?=$produto->nome?></p>
    <h2>R$  <?=$produto->valor?></h2>
   <input type="hidden" name="id_produto" value="">
   <input type="hidden" name="valor" value="">

    <button><a class="compra" href="<?=$produto->link?>">Comprar</a></button>
   </div>
</div>
</form>

<?php
                              }
                            }
                           ?>

</div>

</div>

<script src="app.js"></script>
</body>
</html>