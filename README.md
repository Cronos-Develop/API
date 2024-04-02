# Índice:
- [API](#api)
    - [Apresentação](#apresentação)
    - [Como Funciona](#como-funciona)
        - [Referências](#referências)
        - [Recomendações de Estudo](#recomendações-de-estudo)
    - [Documentação](#documentação)
    - [Deploy](#deploy)
- [Instruções de Execução em Máquina Local]()
    - [Introdução]()
        - [O que é o NGINX]()
    - [Pré-requisitos]()
        - [Docker]()
    - [Como rodar]()
        - [Rodando o projeto pela primeira vez]()
        - [Rodando o projeto pela segunda vez em diante]()
        - [Parando a execução do container]()
        - [Excluindo o container]()
    - [Observações dos desenvolvedores]()
        - [Onde o projeto está rodando?]()
    - [Referências]()
        - [Ambiente Docker]()
        - [NGINX]()
    - [Recomendações de Estudo]()
        - [Playlist de Laravel]()
        - [Playlist de Docker]()
# API:
## Apresentação:
Esta é a API do aplicativo Cronos-Develop, ela será desenvolvida pelos seguintes membros do grupo:
- [Luis Felipe Krause de Castro]()
- [João Victor Ribeiro Santos]()
- [Luiz Filipe de Souza Alves]()

## Como Funciona:
### Referências:
### Recomendações de Estudo:
## Documentação:
## Deploy:

# Instruções de Execução em Máquina Local:
## Introdução:
Estas instruções servem para quem quiser executar esta API na máquina local. Seja para motivos didáticos ou seja para a própria equipe de desenvolvimento deste projeto. Esta API possui os fins descritos acima e pode ainda estar em desenvolvimento. Neste caso, antes de usá-la, recomenda-se buscar contato dos criadores caso encontre algum problema ou faça alguma correção. Agradeço pela paciência e lhe desejo uma boa leitura.

### O que é o NGINX:
Você já ouviu falar em APACHE? Pois bem, tanto o NGINX como o APACHE possuem a função de serem servidores locais para execução de protocolos HTTP. Ele possui a função de possibilitar a navegação HTTP e "emular" os projetos na máquina local. Com esta "emulação", você poderá ver como o seu projeto funcionaria na prática e já de antemão, fazer correções e ajustes para um melhor funcionamento online. Portanto, o NGINX é um servidor local, sendo uma opção gratuita, de código aberto e mais leve para rodar projetos HTTP na sua máquina local.

## Pré-requisitos:
Aqui está tudo que você precisa possuir na sua máquina para rodar este ambiente no seu navegador.

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
- Dentro da pasta API, você iniciará o app do docker se estiver no windows (se estiver no Linux não precisa iniciar o app) e digitará no terminal o seguinte comando:
```
    docker-compose up -d --build
```
- Neste caso, o docker criará as imagens e os containeres e iniciará o container, neste ponto, você já pode rodar o projeto e quando <strong>não for usar mais, visite</strong>[Parando a execução do container]()

### Rodando o projeto pela segunda vez em diante:
- Se você está aqui, você já visitou [Rodando o projeto pela primeira vez](), neste caso, você já possui a imagem e o container da aplicação, com isto, você já pode digitar o comando:
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
### Playlist de Laravel:
### Playlist de Docker:
