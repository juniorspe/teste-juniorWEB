const form = document.querySelector("#form")
const resultado = document.querySelector("#resultado")
const nomeInput = document.querySelector("#nome")
const corpoTabela = document.querySelector("#corpo-tabela")
const botaoGravar = document.querySelector("#gravar")
const botaoLer = document.querySelector("#ler")

const dados = {
  pessoas: []
}

function iniciar() {
  atualizarResultado()
  atualizarTabela()
}

function submit(evento) {
  evento.preventDefault()
  const nome = nomeInput.value
  if (!nome) return
  
  dados.pessoas.push({
    nome,
    filhos: [],
  })

  atualizarResultado()
  atualizarTabela()

  nomeInput.value = ""
}

function atualizarResultado() {
  resultado.value = JSON.stringify(dados, null, 2)
}

function removerPai(nome) {
  return function () {
    dados.pessoas = dados.pessoas.filter(function (pessoa) {
      return pessoa.nome !== nome
    })
    atualizarTabela()
    atualizarResultado()
  }
}
 
function removerFilho(nomeDoPai, nomeDoFilho) {
  return function () {
    const pai = dados.pessoas.find(function (pessoa) {
      return pessoa.nome === nomeDoPai
    })
    pai.filhos = pai.filhos.filter(function (filho) {
      return filho !== nomeDoFilho
    }) 
    atualizarTabela()
    atualizarResultado()
  }
}

function criarLinhaPessoa(nomeDoPai, nomeDoFilho, eFilho) {
  const linha = document.createElement("tr")
  const colunaNome = document.createElement("td")
  const colunaAcao = document.createElement("td")

  const botaoRemover = document.createElement("button")
  botaoRemover.innerHTML = eFilho ? "Remover Filho" : "Remover"
  botaoRemover.onclick = eFilho ? removerFilho(nomeDoPai, nomeDoFilho) : removerPai(nomeDoPai)

  colunaNome.innerHTML = eFilho ? nomeDoFilho : nomeDoPai

  colunaAcao.appendChild(botaoRemover)
  linha.appendChild(colunaNome)
  linha.appendChild(colunaAcao)

  return linha
}

function adicionarFilho(nomeDoPai) {
  return function () {
    const nomeDoFilho = prompt("Digite o nome do filho:")
    if (!nomeDoFilho) return
    const pai = dados.pessoas.find(function (pessoa) {
      return pessoa.nome === nomeDoPai
    })
    pai.filhos.push(nomeDoFilho)
    atualizarTabela()
    atualizarResultado()
  }
}

function atualizarTabela() {
  corpoTabela.innerHTML = ""
  for (let pai of dados.pessoas){
    const linhaPai = criarLinhaPessoa(pai.nome)
    corpoTabela.appendChild(linhaPai)

    for (let filho of pai.filhos) {
      const linhaFilho = criarLinhaPessoa(pai.nome, filho, true)
      corpoTabela.appendChild(linhaFilho)
    }

    const linhaAdicionarFilho = document.createElement("tr")
    const colunaAdicionarFilho = document.createElement("td")

    const botaoAdicionarFilho = document.createElement("button")
    botaoAdicionarFilho.innerHTML = "Adicionar Filho"
    botaoAdicionarFilho.onclick = adicionarFilho(pai.nome)

    colunaAdicionarFilho.colSpan = "2"
    colunaAdicionarFilho.appendChild(botaoAdicionarFilho)
    linhaAdicionarFilho.appendChild(colunaAdicionarFilho)
    corpoTabela.appendChild(linhaAdicionarFilho)
  }
}

async function gravarDados() {
  try {
    const url = "server/gravar.php"
    const body = resultado.value
    const opcoes = {
      method: "POST",
      body, 
    }
    await fetch(url, opcoes) 
    alert("Sucesso") 
  } catch (erro) {
    alert("Erro no servidor: " + erro.message)
  }
}

async function lerDados() {
  try { 
    const resposta = await fetch("server/ler.php")
    const dadosResposta = await resposta.json()
    dados.pessoas = dadosResposta.pessoas
    atualizarTabela()
    atualizarResultado()
  } catch (erro) {
    alert("Erro no servidor: " + erro.message)
  }
}

window.addEventListener('load', iniciar)
form.addEventListener("submit", submit)
botaoGravar.addEventListener("click", gravarDados)
botaoLer.addEventListener("click", lerDados)
