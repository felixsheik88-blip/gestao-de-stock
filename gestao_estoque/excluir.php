<?php
include 'conexao.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $sql = "DELETE FROM produto WHERE id_produto='$id'";
    $conn->query($sql);
}
header("Location: index.php?sucesso=excluido");
?>