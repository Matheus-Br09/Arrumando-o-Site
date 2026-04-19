<?php
session_start();
require_once __DIR__ . '/php/config.php';
include __DIR__ . '/php/get_carrinho.php';

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
    <link rel="stylesheet" href="./css/index.css">
</head>
<body>

<header class="header-topo">
    <button id="toggleMenu" class="btn-menu">☰</button>
    <a href="index.php" id="imagem">
        <img src="./Imagens-Referencias/logo-melhorada.png" class="logo">
    </a>
    <?php if (isset($_SESSION['usuario_id']) && $_SESSION['perfil'] === 'cliente'): ?>
            <span style="color: gold;
                         MARGIN-RIGHT: 15PX;
                        ">
                Olá, <?php echo explode(' ', $_SESSION['usuario_nome'])[0]; ?>!
            </span>
    <div id="divPesquisa">
        <form onsubmit="buscar(); return false;">
            <input type="text" id="pesquisa" placeholder="Pesquisar...">
            <button onclick="buscar()">Buscar</button>
        </form>    
        <div id="areaBusca">
            <h3 id="nome-res"></h3>
            <p id="resultados"></p>
        </div>
    </div>
    
    <div class="user-area">
        
            
            <a href="./pages/meus_pedidos.php" style="color: white; text-decoration: none; margin-right: 15px">MEUS PEDIDOS</a>
            <button id="link">SAIR</button>
        <?php else: ?>
            <a href="./pages/login.php" style="color: white; text-decoration: none;">LOGIN</a>
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
           
        </nav>

        <nav>
            <a href="https://www.instagram.com/mipatisserie_/" target="_blank">
            <img id="instagram" class="instagram" 
            src="./Imagens-Referencias/instagram-icone.png">
            </a>
        </nav>
        
    </aside>

    <main class="conteudo-principal">
        <h1 id="boas-vindas">Bem Vindos a Mi-Patisserie</h1>
        <?php 
        // Reseta o ponteiro para o início para poder usar o loop de categorias novamente
        mysqli_data_seek($res_categorias, 0); 

        // SEGUNDO LOOP: Produtos por Categoria
        while($cat = $res_categorias->fetch_assoc()): ?>
            <section class="titulo-sessao" id="cat-<?php echo $cat['c_categoria']; ?>">
                <h2 id="nome_categoria"><?php echo strtoupper($cat['nome_categoria']); ?></h2>
            </section>

            <section class="produtos-grid">
                <?php
                $id_cat = $cat['c_categoria'];
                $sql_produtos = "SELECT * FROM produto WHERE c_categoria = $id_cat";
                $res_produtos = $conexao->query($sql_produtos);

                if($res_produtos->num_rows > 0):
                    while($prod = $res_produtos->fetch_assoc()): ?>
                        <div class="card">
                            <div class="imagem" style="height: 150px;">
                                <img src="./img/<?php echo $prod['imagem']; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <p class="nome-produto" style="color: white; margin-top: 10px;"><?php echo $prod['nome_produto']; ?></p>
                            <p style="color: gold; font-weight: bold;">R$ <?php echo number_format($prod['preco'], 2, ',', '.'); ?></p>
                            
                            <?php if (isset($_SESSION['usuario_id'])): ?>
                                <button onclick="adicionarItem(<?= $prod['c_produto']; ?>)" 
                                class="btn-add" 
                                style="background: gold; box-shadow: 2px 2px black; padding: 5px 10px; color: black; border: 1px solid gold; border-radius: 5px; display: inline-block;">
                                    Adicionar
                                </button>
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
    <div class="carrinho-fixo" id="valorCarrinho" onclick="window.location='./pages/carrinho.php'" style="position: fixed; bottom: 20px; right: 20px; background: white; padding: 15px; border-radius: 50px; box-shadow: 0 4px 10px rgba(0,0,0,0.3); cursor: pointer;">
        🛒
        <?php echo "R$ " . number_format($total_carrinho, 2, ',', '.'); ?>
    </div>
<?php endif; ?>

<footer style="text-align: center; color: white; padding: 40px 0;">
    <p>Mi Patisserie © 2026</p>
</footer>

<script src="./JAVASCRIPT/Index.js"></script>
</body>
</html>