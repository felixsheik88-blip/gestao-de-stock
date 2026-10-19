<?php
include 'conexao.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $nome = $conn->real_escape_string($_POST['nome_produto']);
    $categoria = $_POST['id_categoria'];
    $qtd = $_POST['quantidade'];
    $preco = $_POST['preco_unitario'];

    if(isset($_POST['id_produto']) && $_POST['id_produto'] != ''){
        $id = $_POST['id_produto'];
        $sql = "UPDATE produto SET nome_produto='$nome', id_categoria='$categoria', 
                quantidade='$qtd', preco_unitario='$preco' WHERE id_produto='$id'";
        $msg = "atualizado";
    } else {
        $sql = "INSERT INTO produto (nome_produto, id_categoria, quantidade, preco_unitario) 
                VALUES ('$nome', '$categoria', '$qtd', '$preco')";
        $msg = "cadastrado";
    }

    if($conn->query($sql) === TRUE){
        header("Location: index.php?sucesso=$msg");
    } else {
        echo "Erro: " . $conn->error;
    }
}
?>