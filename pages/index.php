<?php
session_start();
include 'C:\xampp\htdocs\teste\php\config.php'; 

// 3. Busca as categorias
$sql_categorias = "SELECT * FROM categorias";
$res_categorias = $conexao->query($sql_categorias);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Patisserie</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>

<header class="header-topo">
    <button id="toggleMenu" class="btn-menu">☰</button>
       
    

    <div id="divPesquisa">
        <input type="text" id="pesquisa" placeholder="Pesquisar...">
        <button onclick="buscar()">Buscar</button>
    </div>
    <a href="#inicio">
        <img src="../Imagens-Referencias/logo-melhorada.png" class="logo">
    </a>
    <div class="user-area">
        <?php if (isset($_SESSION['usuario_id']) && $_SESSION['perfil'] === 'cliente'): ?>
            <span style="color: gold; margin-right: 15px;">
                Olá, <?php echo explode(' ', $_SESSION['usuario_nome'])[0]; ?>!
            </span>
            
            <a href="meus_pedidos.php" style="color: white; text-decoration: none; margin-right: 15px;">MEUS PEDIDOS</a>
            <button id="link" style="color: #ff4d4d; text-decoration: none; font-weight: bold;">SAIR</button>
        <?php else: ?>
            <a href="login.php" style="color: white; text-decoration: none; margin-right: 15px;">LOGIN</a>
        <?php endif; ?>
    </div>
    
</header>

<section class="secao-container">
    <aside class="header-lateral">
        <nav class="menu">
            <?php 
            // PRIMEIRO LOOP: Menu Lateral
            if($res_categorias->num_rows > 0):
                while($cat = $res_categorias->fetch_assoc()): ?>
                    <a href="#cat-<?php echo $cat['c_categoria']; ?>">
                        <?php echo $cat['nome_categoria']; ?>
                    </a>
                <?php endwhile; 
            endif; ?>
            <nav class="menu">
            <a href="historico.php">Pedidos Anteriores</a>
            </nav>
        </nav>
    </aside>

    <main class="conteudo-principal">
        <?php 
        // --- O PULO DO GATO ---
        // Reseta o ponteiro para o início para poder usar o loop de categorias novamente
        mysqli_data_seek($res_categorias, 0); 

        // SEGUNDO LOOP: Produtos por Categoria
        while($cat = $res_categorias->fetch_assoc()): ?>
            <section class="titulo-sessao" id="cat-<?php echo $cat['c_categoria']; ?>">
                <h2 style="color: white; margin-top: 20px;"><?php echo strtoupper($cat['nome_categoria']); ?></h2>
            </section>

            <section class="produtos-grid" style="display: flex; flex-wrap: wrap; gap: 20px;">
                <?php
                $id_cat = $cat['c_categoria'];
                $sql_produtos = "SELECT * FROM produto WHERE c_categoria = $id_cat";
                $res_produtos = $conexao->query($sql_produtos);

                if($res_produtos->num_rows > 0):
                    while($prod = $res_produtos->fetch_assoc()): ?>
                        <div class="card" style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 10px; width: 200px;">
                            <div class="imagem" style="height: 150px; background: #eee;">
                                <img src="img/<?php echo $prod['imagem']; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <p style="color: white; margin-top: 10px;"><?php echo $prod['nome_produto']; ?></p>
                            <p style="color: gold; font-weight: bold;">R$ <?php echo number_format($prod['preco'], 2, ',', '.'); ?></p>
                            
                            <?php if (isset($_SESSION['usuario_id'])): ?>
                                <a href="adicionar_carrinho.php?id=<?php echo $prod['c_produto']; ?>" class="btn-add" style="background: gold; padding: 5px 10px; text-decoration: none; color: black; border-radius: 5px; display: inline-block;">
                                    Adicionar
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; 
                else:
                    echo "<p style='color: gray;'>Nenhum produto nesta categoria.</p>";
                endif; ?>
            </section>
        <?php endwhile; ?>
    </main>
</section>

<?php if (isset($_SESSION['usuario_id']) && $_SESSION['perfil'] === 'cliente'): ?>
    <div class="carrinho-fixo" onclick="window.location='carrinho.php'" style="position: fixed; bottom: 20px; right: 20px; background: white; padding: 15px; border-radius: 50px; box-shadow: 0 4px 10px rgba(0,0,0,0.3); cursor: pointer;">
        🛒 
        <?php
        $total_carrinho = 0;
        if (isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho'])) {
            foreach ($_SESSION['carrinho'] as $id => $qtd) {
                $res_p = $conexao->query("SELECT preco FROM produto WHERE c_produto = ".intval($id));
                if ($p = $res_p->fetch_assoc()) {
                    $total_carrinho += $p['preco'] * $qtd;
                }
            }
        }
        echo "R$ " . number_format($total_carrinho, 2, ',', '.');
        ?>
    </div>
<?php endif; ?>

<footer style="text-align: center; color: white; padding: 40px 0;">
    <p>Mi Patisserie © 2026</p>
</footer>   

<script src="../JAVASCRIPT/Index.js"></script>
</body>
</html>