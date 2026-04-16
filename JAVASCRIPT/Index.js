let carrinho = [];
let total = 0;

function adicionarItem(nome, preco) {
    carrinho.push({ nome, preco });
    total += preco;
    atualizarCarrinho();
}

// function atualizarCarrinho(){
//     let lista = document.getElementById("listaCarrinho");
//     let totalTexto = document.getElementById("total");

//     lista.innerHTML = "";

//     carrinho.forEach(item => {
//         let li = document.createElement("li");
//         li.innerText = item.nome + " - R$ " + item.preco.toFixed(2);
//         lista.appendChild(li);
//     });

//     totalTexto.innerText = "Total: R$ " + total.toFixed(2);
// }

// function finalizarPedido(){
//     if(carrinho.length === 0) return;

//     let historico = JSON.parse(localStorage.getItem("historico")) || [];
//     historico.push(carrinho);

//     localStorage.setItem("historico", JSON.stringify(historico));

//     carrinho = [];
//     total = 0;

//     atualizarCarrinho();
//     carregarHistorico();
// }

// function carregarHistorico(){
//     let lista = document.getElementById("historicoLista");
//     lista.innerHTML = "";

//     let historico = JSON.parse(localStorage.getItem("historico")) || [];

//     historico.forEach((pedido, i) => {
//         let li = document.createElement("li");
//         li.innerText = "Pedido " + (i+1) + " (" + pedido.length + " itens)";
//         lista.appendChild(li);
//     });
// }



// Buscar

function buscar(event) {
    let texto = document.getElementById("pesquisa").value.toLowerCase();
    let cards = document.querySelectorAll(".card");
    let nome_res = document.getElementById("nome-res")
    let resultados = document.getElementById("resultados");

    resultados.innerHTML = "";

    if (texto === "") {
        resultados.textContent = "Digite algo válido"
        return;
    }

    let encontrado

    const titulos = document.querySelectorAll(".nome-produto")
    
    titulos.forEach(p => {
        if (p.innerHTML.toLowerCase().startsWith(texto)) {
            nome_res.textContent = "Resultados para \"" + texto + "\"";
            resultados.appendChild(p.closest(".card").cloneNode(true))
            resultados.style.display = "flex"
            resultados.style.padding = '40px'
            resultados.style.margin = '10px'
            resultados.style.bottom = '10px'
            resultados.style.borderRadius = '10px'
            encontrado = true;
        }
    })

    if (!encontrado) {
        resultados.textContent = "Nenhum produto/alimento com esse nome foi encontrado!"
    }

    let areaBusca = document.getElementById("areaBusca")

    document.addEventListener("click", function(event){
        const clicouDentro = areaBusca.contains(event.target);

        if (!clicouDentro){
            resultados.style.display = "none";
            resultados.innerHTML = ""
            nome_res.innerHTML = ""
        }
    })
};

document.getElementById('link').addEventListener('click', function (event) {
    event.preventDefault()
    chamarPHP();
})

//Função de logout
function chamarPHP() {
    fetch('./php/logout.php')
    location.reload()
}

// Exclusão de produto (pagina admin)

function excluirProduto(id) {
    if (!confirm("Tem certeza? ")) return;

    fetch('../php/excluir_produto.php?id=' + id)
        .then(response => response.text())
        .then(data => {
            alert('Produto excluído com sucesso!!')
            location.reload();
        })
        .catch(error => {
            console.error("Erro: ", error)
            alert('Erro ao excluir produto!!')
        })
}

//Adicionar item no carrinho

function adicionarItem(id){
    fetch('./php/adicionar_carrinho.php?id=' + id)
    .then(response => response.text())
    .then(data => {
        console.log("Produto adicionado!!")
        location.reload()
    })
    .catch(error => {
        console.log('Erro: ', error)
    })
    
}

// atualizar carrinho

function atualizarCarrinho(){
    fetch('./php/get_carrinho.php')
    .then(res => res.json())
    .then(data => {
        document.getElementById("valorCarrinho").textContent = data.total;
    })
}


let index = 0;

function avancar() {
    let carrossel = document.getElementById("carrossel");
    index = (index + 1) % 3;
    carrossel.style.transform = "translateX(-" + (index * 315) + "px)";
}

function voltar() {
    let carrossel = document.getElementById("carrossel");
    index = (index - 1 + 3) % 3;
    carrossel.style.transform = "translateX(-" + (index * 315) + "px)";
}
// bagui de recolher o bagui

const botaoTopo = document.getElementById("toggleMenu");
const sidebar = document.querySelector(".header-lateral");
const body = document.body;

function toggleMenu() {
    sidebar.classList.toggle("recolhido");
    body.classList.toggle("recolhido");
}

if (botaoTopo) {
    botaoTopo.addEventListener("click", toggleMenu);
}
