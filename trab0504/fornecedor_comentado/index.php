<?php
// Inclui os arquivos de classe necessários
include_once 'conexao.php';
include_once 'Usuario.php';

// Cria uma nova instância da classe de conexão
$conexao_banco = new Conexao();
// Conecta-se ao banco de dados
$banco = $conexao_banco->conectar();

// Cria uma nova instância da classe Fornecedor, passando o objeto de conexão como parâmetro
$Usuario = new Usuario($banco);

// Verifica se a requisição foi feita com o método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se os campos do formulário estão preenchidos
    if (!empty($_POST['Usuario']) && !empty($_POST['telefone'])) {
        // Atribui os valores dos campos do formulário às propriedades do objeto Fornecedor
        $Usuario->Usuario = $_POST['Usuario'];
        $Usuario->telefone = $_POST['telefone'];

        // Tenta criar um novo fornecedor no banco de dados
        if ($Usuario->criar()) {
            echo "<p>Usuario criado com sucesso.</p>";
        } else {
            echo "<p>Não foi possível criar o Usuario.</p>";
        }
    } else {
        echo "<p>Por favor, preencha todos os campos.</p>";
    }
}

// Executa a consulta para ler todos os fornecedores cadastrados
$stmt = $Usuario->ler();
// Obtém o número de linhas retornadas pela consulta
$num = $stmt->rowCount();

// Verifica se há fornecedores cadastrados
if ($num > 0) {
    echo "<h2>Usuario Cadastrados</h2>";
    echo "<div class='lista-Usuario'>";
    echo "<ul>";
    // Itera sobre os resultados da consulta e exibe cada fornecedor
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        echo "<li>ID: {$id}, Usuario: {$Usuario}, telefone: {$telefone}</li>";
    }
    echo "</ul>";
    echo "</div>";
} else {
    echo "<p>Nenhum Usuario cadastrado.</p>";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Usuario</title>
    <link rel="stylesheet" href="estilo.css"> <!-- Inclui o arquivo de estilo CSS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> <!-- Inclui a biblioteca jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script> <!-- Inclui a biblioteca jQuery Mask -->
</head>
<body>
    <div class="container">
        <h2>Adicionar Novo Usuario</h2>
        <!-- Formulário para adicionar um novo Usuario -->
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="Usuario">Usuario:</label>
            <input type="text" id="Usuario" name="Usuario" required> <!-- Campo de texto para o nome do Usuario -->

            <label for="telefone">telefone:</label>
            <input type="text" id="telefone" name="telefone" required> <!-- Campo de texto para o número de celular do Usuario -->

            <input type="submit" value="Adicionar Usuario"> <!-- Botão de envio do Usuario -->
        </form>
    </div>
    <script>
        $(document).ready(function(){
            $('#telefone').mask('(00)0 0000-0000'); // Aplica a máscara de formatação para o campo de telefone usando jQuery Mask
        });
    </script>
</body>
</html>
