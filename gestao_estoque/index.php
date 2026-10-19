<?php include 'conexao.php'; 


$editar = null;
if(isset($_GET['editar'])){
    $id = $_GET['editar'];
    $editar = $conn->query("SELECT * FROM produto WHERE id_produto='$id'")->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Sistema de Gestão de Estoque</title>
<style>
    body { font-family: Arial, sans-serif; background: #f0f2f5; margin: 0; padding: 20px; }
    .container { max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 10px; }
    h1 { background: #2c3e50; color: white; padding: 15px; border-radius: 5px; text-align: center; }
    .cards { display: flex; gap: 20px; margin-bottom: 20px; }
    .card { flex: 1; background: #3498db; color: white; padding: 20px; border-radius: 8px; text-align: center; }
    .card p { margin: 5px 0 0; font-size: 24px; font-weight: bold; }
    form { background: #ecf0f1; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
    form input, form select { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
    button { background: #27ae60; color: white; border: none; padding: 10px 20px; margin-top: 15px; border-radius: 4px; cursor: pointer; }
    .btn-editar { background: #f39c12; padding: 5px 10px; text-decoration: none; color: white; border-radius: 4px; }
    .btn-excluir { background: #e74c3c; padding: 5px 10px; text-decoration: none; color: white; border-radius: 4px; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    table th { background: #34495e; color: white; padding: 10px; }
    table td { padding: 10px; border: 1px solid #ddd; text-align: center; }
    .sucesso { background: #2ecc71; color: white; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; }
</style>
</head>
<body>
<div class="container">
    <h1>Sistema de Gestão de Estoque</h1>

    <?php if(isset($_GET['sucesso'])): 
        $msgs = ['cadastrado'=>'Produto cadastrado!', 'atualizado'=>'Produto atualizado!', 'excluido'=>'Produto excluído!'];
    ?>
        <div class="sucesso"><?= $msgs[$_GET['sucesso']] ?></div>
    <?php endif; ?>

    <?php
    $total = $conn->query("SELECT COUNT(*) as total FROM produto")->fetch_assoc()['total'];
    $valor = $conn->query("SELECT SUM(quantidade * preco_unitario) as valor FROM produto")->fetch_assoc()['valor'];
    ?>
    <div class="cards">
        <div class="card"><h3>TOTAL DE PRODUTOS</h3><p><?= $total ?></p></div>
        <div class="card"><h3>VALOR DO ESTOQUE</h3><p><?= number_format($valor ?? 0, 2, ',', '.') ?> MT</p></div>
    </div>

    <h2><?= $editar ? 'Editar Produto' : 'Cadastrar Novo Produto' ?></h2>
    <form action="salvar_produto.php" method="POST">
        <input type="hidden" name="id_produto" value="<?= $editar['id_produto'] ?? '' ?>">
        
        <label>NOME DO PRODUTO:</label>
        <input type="text" name="nome_produto" value="<?= $editar['nome_produto'] ?? '' ?>" required>

        <label>CATEGORIA:</label>
        <select name="id_categoria" required>
            <option value="">Selecionar categoria</option>
            <?php
            $categorias = $conn->query("SELECT * FROM categoria ORDER BY nome");
            while($cat = $categorias->fetch_assoc()){
                $selected = ($editar && $editar['id_categoria'] == $cat['id_categoria']) ? 'selected' : '';
                echo "<option value='".$cat['id_categoria']."' $selected>".$cat['nome']."</option>";
            }
            ?>
        </select>

        <label>QUANTIDADE:</label>
        <input type="number" name="quantidade" value="<?= $editar['quantidade'] ?? '0' ?>" min="0" required>

        <label>PREÇO UNITÁRIO MT:</label>
        <input type="number" step="0.01" name="preco_unitario" value="<?= $editar['preco_unitario'] ?? '0.00' ?>" min="0" required>

        <button type="submit"><?= $editar ? 'Atualizar Produto' : '+ Cadastrar Produto' ?></button>
        <?php if($editar): ?><a href="index.php"><button type="button" style="background:#7f8c8d">Cancelar</button></a><?php endif; ?>
    </form>

    <h2>Produtos Cadastrados</h2>
    <table>
        <tr>
            <th>Produto</th><th>Categoria</th><th>Qtd</th><th>Preço</th><th>Total</th><th>Ações</th>
        </tr>
        <?php
        $sql = "SELECT p.*, c.nome as cat_nome FROM produto p 
                LEFT JOIN categoria c ON p.id_categoria = c.id_categoria ORDER BY p.id_produto DESC";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()){
            $total_item = $row['quantidade'] * $row['preco_unitario'];
            echo "<tr>
                <td>".$row['nome_produto']."</td>
                <td>".$row['cat_nome']."</td>
                <td>".$row['quantidade']."</td>
                <td>".number_format($row['preco_unitario'],2,',','.')." MT</td>
                <td>".number_format($total_item,2,',','.')." MT</td>
                <td>
                    <a href='index.php?editar=".$row['id_produto']."' class='btn-editar'>Editar</a>
                    <a href='excluir.php?id=".$row['id_produto']."' class='btn-excluir' onclick='return confirm(\"Tem certeza?\")'>Excluir</a>
                </td>
            </tr>";
        }
        ?>
    </table>
</div>
</body>
</html>