# Índice:
- [API](#api)
    - [Apresentação](#apresentação)
    - [Como Funciona](#como-funciona)
        - [Referências](#referências)
        - [Recomendações de Estudo](#recomendações-de-estudo)
    - [Documentação](#documentação)
    - [Deploy](#deploy)
- [Instruções de Execução em Máquina Local](#instruções-de-execução-em-máquina-local)
    - [Introdução](#introdução)
        - [O que é o NGINX](#o-que-é-o-nginx)
    - [Pré-requisitos](#pré-requisitos)
        - [Git](#git)
        - [Composer](#composer)
        - [Docker](#docker)
    - [Como rodar](#como-rodar)
        - [Rodando o projeto pela primeira vez](#rodando-o-projeto-pela-primeira-vez)
        - [Rodando o projeto pela segunda vez em diante](#rodando-o-projeto-pela-segunda-vez-em-diante)
        - [Parando a execução do container](#parando-a-execução-do-container)
        - [Excluindo o container](#excluindo-o-container)
    - [Observações dos desenvolvedores](#observações-dos-desenvolvedores)
        - [Onde o projeto está rodando?](#onde-o-projeto-está-rodando)
    - [Referências](#referências-1)
        - [Ambiente Docker](#ambiente-docker)
        - [NGINX](#nginx)
    - [Recomendações de Estudo](#recomendações-de-estudo-1)
        - [Playlist de Laravel](#playlist-de-laravel)
        - [Playlist de Docker](#playlist-de-docker)
- [Créditos](#créditos)
# API:
## Apresentação:
Esta é a API do aplicativo Cronos-Develop.

## Desenvolvedores:
- [Luis Felipe Krause de Castro](https://github.com/LuisFelipeKrause)
- [João Victor Ribeiro Santos](https://github.com/Carecovisk)
- [Luiz Filipe de Souza Alves](https://github.com/LuFi-1227)

## Como Funciona:
### Referências:
### Recomendações de Estudo:
## Documentação:

### Recebendo dados via requisição GET
Para receber dados via requisição GET, use a rota `/api/users/{email:password}/{hash}`, caso queira um usuário específico. Substitua `{email:password}` com os dados do usuário que deseja encontrar. Use os dados no formato `email:password`. Em caso verdadeiro, a resposta será o id do usuário.
O `hash` recebido após o email e senha enviados pela URL é a identificação do usuário que está logado. Será usado para garantir que o usuário tem a permissão necessária para executar determinada ação. 

#### Exemplo Requisição GET:

```
GET /api/users/johndoe@example.com:password123
```

#### Resposta Esperada:

```
{
  "id": 1
}
```

A resposta será um objeto JSON contendo o id do usuário encontrado. Se o usuário não for encontrado ou a senha estiver incorreta, a API retornará um erro 404 ou 401 respectivamente, em formato JSON.

### Enviando dados via requisição POST

Para enviar dados via requisição POST, utilize a rota `/api/users/{hash}`. Envie os dados do usuário no corpo da requisição no formato JSON, com os campos name, email e password.
O `hash` recebido após `/users` é a identificação do usuário que está logado. Será usado para garantir que o usuário tem a permissão necessária para executar determinada ação.

#### Exemplo de corpo da solicitação POST:

```
http://127.0.0.1:8000/api/users/H50$du*e2
{
    "name": "João Caramelo Bittencourt Sucessada",
    "cpf": "139.159.846-63",
	"email": "joao.bittencourt@protonmail.com.br",
	"password": "password123"
}
```

#### Resposta esperada:

```
{
	"message": "Data validated successfully",
	"userHash_URL": "H50$du*e2",
	"Id": "B51@B53@i52%G51.",
	"name": "João Caramelo Bittencourt Sucessada",
	"cpf": "13915984663",
	"email": "joao.bittencourt@protonmail.com.br",
	"password": "$2y$12$KB7jqkiC3FsKDFmva9zSEOciJhcUxYm8NssypyRSc6oL0dq9fTGZa"
}
```

A resposta será um objeto JSON contendo uma mensagem de sucesso junto com os dados enviados. Se houver erros de validação nos dados enviados, a API retornará uma resposta com os erros específicos e um código de status 422, no formato JSON.

### 

## Deploy:

# Instruções de Execução em Máquina Local:
## Introdução:
Estas instruções servem para quem quiser executar esta API na máquina local. Seja para motivos didáticos ou seja para a própria equipe de desenvolvimento deste projeto. Esta API possui os fins descritos acima e pode ainda estar em desenvolvimento. Neste caso, antes de usá-la, recomenda-se buscar contato dos criadores caso encontre algum problema ou faça alguma correção. Agradeço pela paciência e lhe desejo uma boa leitura.

### O que é o NGINX:
Você já ouviu falar em APACHE? Pois bem, tanto o NGINX como o APACHE possuem a função de serem servidores locais para execução de protocolos HTTP. Ele possui a função de possibilitar a navegação HTTP e "emular" os projetos na máquina local. Com esta "emulação", você poderá ver como o seu projeto funcionaria na prática e já de antemão, fazer correções e ajustes para um melhor funcionamento online. Portanto, o NGINX é um servidor local, sendo uma opção gratuita, de código aberto e mais leve para rodar projetos HTTP na sua máquina local.

## Pré-requisitos:
Aqui está tudo que você precisa possuir na sua máquina para rodar este ambiente no seu navegador.

### Git:
- O Git e o GitBash são ferramentas do Git linkadas com GitHub que são necessárias para o gerenciamento das versões do projeto. Essas ferramentas foram criadas justamente para facilitar o versionamento e o upload dos projetos para serem salvos ou para análise de código que pode ser feita com o objetivo de correção ou revisão pelos próprios programadores.
- Para dar o git clone do repositório, você precisará ter o [gitbash](https://git-scm.com/downloads) instalado na sua máquina.
- Link para instalar o Git:
- [link](https://git-scm.com/downloads)

### Composer:
- O composer é um gerenciador de dependências e arquivos que é necessário para rodar o projeto localmente, pois ele instala tudo que você precisará na sua máquina com um simples comando.
- [Instalação do Composer](https://getcomposer.org/download/)
  
### Docker:
- O docker é uma aplicação que funciona como uma máquina virtual para que se rode o mesmo ambiente de desenvolvimento em máquinas diferentes. Nele, você pode baixar ou criar algo como partições chamadas de Imagens, que possuem todos os arquivos necessários para um ambiente de desenvolvimento e containeres, que são meios de executar um conjunto não vazio de imagens como processos no seu sistema operacional. Ou seja, no final, o docker é uma forma de ter vários ambientes virtuais no seu sistema operacional para executar projetos diferentes, por exemplo, com o docker você pode ter todas as versões do PHP na sua máquina e escolher qual você gostaria de ter no momento para um projeto específico e depois se você quiser executar seu projeto em outra versão, basta pausar uma instância e iniciar outra. Portanto é uma ferramenta muito importante de se conhecer se você for desenvolvedor.
- Para instalar o docker, basta você visitar o site oficial e seguir a documentação da página para instalação no seu ambiente:
- [Baixar o docker](https://www.docker.com/products/docker-desktop/)

## Como rodar:
- Para rodar o projeto na sua máquina é muito simples, basta seguir o passo a passo.

### Rodando o projeto pela primeira vez:
- Possuindo o docker e o Git na sua máquina, para você rodar o projeto pela primeira vez, você vai precisar clonar o repositório para sua máquina, neste caso, basta entrar no CMD ou terminal e então ir até uma pasta na qual você possa clonar o projeto, daí basta digitar no terminal:
```
    git clone https://github.com/Cronos-Develop/API.git
```
- Agora, digite o seguinte comando:
```
   cd API
```
- Antes de iniciar o docker e dentro da pasta API, você precisará da instalação de dependências pelo Composer, nesse caso, digite o seguinte comando no seu terminal (Com o Composer instalado):
```
    composer update
```
- Fique tranquilo que o processo demora um pouco mais de 10 minutos para terminar. Ainda dentro da pasta API, você iniciará o app do docker se estiver no windows (se estiver no Linux não precisa iniciar o app) e digitará no terminal o seguinte comando:
```
    docker-compose up -d --build
```
- Neste caso, o docker criará as imagens e os containeres e iniciará o container, neste ponto, você já pode rodar o projeto e quando <strong>não for usar mais, visite</strong>[Parando a execução do container](#parando-a-execução-do-container)

### Rodando o projeto pela segunda vez em diante:
- Se você está aqui, você já visitou [Rodando o projeto pela primeira vez](#rodando-o-projeto-pela-primeira-vez), neste caso, você já possui a imagem e o container da aplicação, com isto, você já pode digitar o comando:
```
    docker-compose up -d
```
- Agora o container multi imagens já está rodando na sua máquina.

### Parando a execução do container:
- Se você está aqui, o container criado está rodando na sua máquina, para pará-lo, digite no terminal o seguinte comando:
```
    docker-compose stop
```
- O container será parado e não haverá mais um processo do docker na sua máquina.

### Excluindo o container:
- Para apagar o conatiner da sua máquina, basta ir até a pasta api pelo terminal e digitar o seguinte comando:
```
    docker-compose down
```
- O container será deletado, agora, para deletar as imagens, você deve digitar no seu terminal:
```
    docker image ls
```
- Agora digite:
```
    docker image rm <nome da imagem>:<tag da imagem>
```
- Onde o nome e a tag da imagem estarão na saída da docker image ls.
- Parabéns, você limpou sua máquina!

## Observações dos desenvolvedores:
Aqui serão colocadas observações que podem ajudar.

### Onde o projeto está rodando?
Para ver onde o projeto está rodando, basta iniciar o container e digitar no navegador o seguinte endereço:
```
    localhost:8080
```
A página deve renderizar pelo NGINX.

## Referências:
Aqui você encontrará todo o embasamento teórico utilizado ao fazer as instruções para execução da API na sua máquina local.

### Ambiente Docker:
- Neste site, o criador do conteúdo ensina como preparar o docker para o uso com o ambiente utilizado no Back-end, neste caso, possuindo qualquer dúvida, esta fonte pode ser de grande ajuda.
    - [Docker e Docker Composer na Prática](https://fullcycle.com.br/docker-e-docker-composer-na-pratica-criando-ambiente-laravel/)

### NGINX:
- Aqui está uma página da Hostinger explicando o que é e como funciona o NGINX.
    - [O que é NGINX e como funciona](https://www.hostinger.com.br/tutoriais/o-que-e-nginx)

## Recomendações de Estudo:
Aqui estamos deixando recomendações de Playlists no YouTube para que se possa estudar este projeto.

### Playlist de Laravel:
- Esta playlist é muito importante no desenvolvimento deste repositório.
- [Laravel](https://www.youtube.com/playlist?list=PLyugqHiq-SKdFqLIM3HgCAnG8_7wUqHMm)

### Playlist de Docker:
- Esta playlist é muito importante no desenvolvimento deste repositório.
- [Docker](https://www.youtube.com/watch?v=4Z-raAFlHf4&list=PLR8JXremim5BWiO-MCaAffQYwFZrD11-j&index=1)

# Créditos:
- Todos os créditos dos materiais aqui utilizados e referenciados são destinados aos criadores dos conteúdos.
