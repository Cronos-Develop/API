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

#### Empresas
Para receber dados via requisição GET, use a rota `/api/empresas/{empresa_id}/{hash}` (no caso de manipulação de empresas), caso queira uma empresa específica. Substitua `{empresa_id}` com o id da empresa que deseja encontrar. Em caso verdadeiro, a resposta será o nome da empresa.

O `hash` recebido no final da URL é a identificação do usuário que está logado. Será usado para garantir que o usuário tem a permissão necessária para executar determinada ação. 


A resposta será um objeto JSON contendo o id do usuário encontrado. Se o usuário não for encontrado ou a senha estiver incorreta, a API retornará um erro 404 ou 401 respectivamente, em formato JSON.

##### Exemplo Requisição GET:

```
GET /api/empresas/7/G5*h2%L9@
```

##### Resposta Esperada:

```
{
	"success": "Kling PLC"
}
```

A resposta será um objeto JSON contendo o nome da empresa encontrada. Se a empresa não for encontrada, a API retornará um erro 404 em formato JSON.

#### Usuários
Para receber dados via requisição GET, use a rota `/api/users/{hash}`, sendo `{hash}` o id do usuário, definido no momento de criação como um hash. Caso essa requisição seja enviada com o hash de um usuário existente, todos os seus dados serão retornados em formato JSON.

##### Exemplo Requisição GET:

```
GET /api/users/e53@a51$C46.g48.I53.
```

##### Resposta Esperada:

```
[
	{
		"id": "e53@a51$C46.g48.I53.",
		"name": "Noa de Oliveira Cervantes Neto",
		"email": "salas.isabella@example.org",
		"telefone": "(64) 95142-9754",
		"senha": "$2y$12$CIwlHerbeHE86KlgGCLN6OWmVU3aA0tOVNzEu.03ZZI1p1r498Igi",
		"endereco": "65897-238, Largo Ítalo Rodrigues, 17\nDiogo do Norte - RO",
		"cep": "453024-307",
		"nascimento": "2020-08-17",
		"empresario": 0,
		"cpf_cnpj": "456.352.060-85",
		"nome_da_empresa": null,
		"created_at": "2024-05-10 16:21:34",
		"updated_at": "2024-05-10 16:21:34"
	}
]
```

Para receber dados via requisição GET, use a rota `/api/users/{cpf:password}/{hash}` (no caso de manipulação de usuários), caso queira um usuário específico. Substitua `{cpf:password}` com os dados do usuário que deseja encontrar. Use os dados no formato `cpf:password`. Em caso verdadeiro, a resposta será o id do usuário.

##### Exemplo Requisição GET:

```
GET /api/users/456.352.060-85:password/userHash
```

##### Resposta Esperada:

```
{
	"id": "e53@a51$C46.g48.I53."
}
```

Para recuperação de senha, via requisição GET, use a rota `/api/recuperar/{cpf_cnpj}`, sendo `{cpf_cnpj}` o CPF/CNPJ do usuário, para que o email possa ser encontrado no banco de dados a partir da consulta do CPF/CNPJ. Caso esteja registrado, o e-mail do usuário é retornado.
Vale lembrar que, caso seja utilizado o CNPJ, que por padrão possui `/` (por exemplo: 41.041.903`/`0001-06), é necessário inserí-lo na URL sem a barra (os outros sinais de pontuação podem ser deixados ou tirados, pois será tratado dentro das funções). Quanto aos outros elementos, não há problema em deixá-los.

##### Exemplo Requisição GET com CPF:

```
GET /api/recuperar/111.223.537-07
```

##### Resposta Esperada:

```
[
	{
		"email": "vasques.sueli@example.org"
	}
]
```
##### Exemplo Requisição GET com CNPJ:

```
GET /api/recuperar/41041903000106
```

##### Resposta Esperada:

```
[
	{
		"email": "rodolfo88@example.com"
	}
]
```

### Deletando dados via requisição DELETE
Para deletar um usuário, use a rota `/api/users/{user}/{hash}`, sendo `{user}` o CPF, CNPJ (valendo a mesma regra citada acima sobre `/`) ou id do usuário.

##### Exemplo Requisição DELETE:

```
DELETE /api/users/A50*J55%G56*a57@/<hash>
```

##### Resposta Esperada:

```
{
	"success": "Usuário deletado com sucesso"
}
```

### Atualizando dados via requisição PUT

Para atualizar os registros de um usuário, use a rota `/users/{user}/{hash}`, sendo `{user}` o id do usuário que terá seus registros atualizados. Os dados a serem atualizados deverão ser enviados em formato JSON, sendo permitido enviar somente os dados que serão alterados.

##### Exemplos Requisição PUT:

```
PUT /api/users/A50*J55%G56*a57@/<hash>

JSON:
{
	"email": "joaocarlos@uft.com",
  "telefone": "(11) 2121-1131"
}
```
```
PUT /api/users/h53!a56&G46*B51.F56./<hash>

JSON:
{
	"name": "Cláudio Arraio Júnior",
      "endereco": "504 sul, alameda 22, lote 44. Palmas-TO",
	"cep": "124596-715"
}
```

#### Resposta esperada:

```
{
	"success": "Dados atualizados com sucesso"
}
```

### Enviando dados via requisição POST

Para enviar dados via requisição POST, no caso dos usuários, utilize a rota `/api/users/{hash}`. Envie os dados do usuário no corpo da requisição no formato JSON, com os campos mostrados abaixo.

Para enviar dados via requisição POST, no caso das empresas, utilize a rota `/api/empresas/{hash}`. Envie os dados da empresa no corpo da requisição no formato JSON, com os campos mostrados abaixo.

O `hash` recebido no fim da URL é a identificação do usuário que está logado. Será usado para garantir que o usuário tem a permissão necessária para executar determinada ação.

#### Exemplo de corpo da solicitação POST para Usuários:

```
POST /api/users/H50$du*e2

{
	"name": "João Caramelo Bittencourt Sucessada",
	"cpf_cnpj": "348.844.070-24",
	"senha": "password123",
	"email": "joao.bittencourt@protonmail.com",
    "telefone": "(63) 8147-0033",
	"endereco": "204 sul, alameda 12, lote 20",
	"cep": "77063-426",
	"nascimento": "2004-03-07",
	"empresario": 0,
	"nome_da_empresa": null
}
```

#### Resposta esperada:

```
{
	"success": "Usuário registrado com sucesso"
}
```
A resposta será um objeto JSON contendo uma mensagem de sucesso. Se houver erros de validação nos dados enviados, a API retornará uma resposta com os erros específicos e um código de status 422, no formato JSON.

#### Exemplo de corpo da solicitação POST para Empresas:

```
POST /api/empresas/H50$du*e2

{
	"usuario_id": "a49,a54=a46%f50.b53.",
	"usuario_parceiro_id": "E51*A50=J46,f52.d51.",
	"nome_da_empresa": "Tortas&Tortas",
	"nicho": "Alimentação e Doceria",
    "resumo": "Restaurante especializado em doces"
}
```

#### Resposta esperada:

```
{
	"success": "Empresa registrada com sucesso"
}
```

A resposta será um objeto JSON contendo uma mensagem de sucesso. Se houver erros de validação nos dados enviados, a API retornará uma resposta com os erros específicos e um código de status 422, no formato JSON.

### Retornando lista de empresas a partir da id do usuário
Para receber a lista de empresas a partir do id do usuario faça uma requisição GET na rota `api/empresas/user/{hash}`, onde `{hash}` é o id do usuario. Um erro 404 é retornado se o usuario não for encontrado ou um corpo vazio se o usuario não tiver empresas.

Exemplo:

```
GET /api/empresas/user/h57.a55.d46.J51.f49.


[
  {
    "id": 3,
    "usuario_id": "h57.a55.d46.J51.f49.",
    "nome_da_empresa": "Vasques e Correia",
    "nicho": "fugit",
    "resumo": "Está entendido: no primeiro ou no segundo mez do anno que vem, irás para o outro, até que exclamei: --Prompto! --Estará bom? --Veja no espelho. Em vez de ir ao espelho, que pensaes que fez Capitú?.",
    "created_at": "2024-05-13T13:36:10.000000Z",
    "updated_at": "2024-05-13T13:36:10.000000Z"
  },
  {
    "id": 4,
    "usuario_id": "h57.a55.d46.J51.f49.",
    "nome_da_empresa": "Domingues Comercial Ltda.",
    "nicho": "perspiciatis",
    "resumo": "São assim de cigana obliqua e dissimulada. Pois, apesar delles, poderia passar, se não fosse a vaidade sobrevivente; mas o momento da saida. Peguei da minha amiga; pensei nisso, cheguei a tental-o.",
    "created_at": "2024-05-13T13:36:10.000000Z",
    "updated_at": "2024-05-13T13:36:10.000000Z"
  }
]
```

### Retornando lista de empresas a partir do id do usuário parceiro
Para retornar todas as empresas que tem parceiria com um usuario, faça um requisição GET na rota `api/empresas/partner/{hash}`, onde `hash` é o id do usuario parceiro. Retorna um erro 404 se o usuario não for encontrado.

Exemplo:

```
GET api/empresas/partner/{hash}

[
  {
    "id": 1,
    "usuario_id": "G53.A51=A53@e47.A48,A54+",
    "nome_da_empresa": "Vieira-da Silva",
    "nicho": "qui",
    "resumo": "Cabral falara da minha consciencia moral sem _deficit._ Mandar dizer cem missas, ou subir do joelhos a ladeira da Gloria para ouvir uma, ir á Terra-Santa, tudo o que tanto póde ser que não se.",
    "created_at": "2024-05-13T13:36:10.000000Z",
    "updated_at": "2024-05-13T13:36:10.000000Z",
    "pivot": {...}
  },
  {
    "id": 2,
    "usuario_id": "d55.A53%H46*e53.d52.",
    "nome_da_empresa": "Solano Comercial Ltda.",
    "nicho": "eum",
    "resumo": "As mãos, a despeito de alguns instantes de concentrarão, veiu ver se eram adequadas e se ajoelhavam á nossa passagem, tudo me enchia a alma de lepidez nova. Padua, ao contrario, os olhos para elles.",
    "created_at": "2024-05-13T13:36:10.000000Z",
    "updated_at": "2024-05-13T13:36:10.000000Z",
    "pivot": {...}
  }
]
```

### Retornando lista de tarefas e subtarefas a partir do id da empresa
Para fazer essa listagem, faça uma requisição GET na rota `api/empresas/{empresa}/tarefas/{hash}`, onde `{empresa}` é o id da empresa e `{hash}` é o id do usuario. Um erro 404 será retornado se a empresa ou usuario não forem encontrados.

Exemplo:

```
GET /api/empresas/1/tarefas/h57.a55.d46.J51.f49.

[
  {
    "id": 1,
    "empresa_id": 1,
    "tarefa": "Omnis autem laudantium quis maxime repudiandae tempore consequatur.",
    "subtarefas": []
  },
  {
    "id": 2,
    "empresa_id": 1,
    "tarefa": "Dignissimos quia nam ut et fuga voluptas enim.",
    "subtarefas": []
  },
  {
    "id": 3,
    "empresa_id": 1,
    "tarefa": "Nihil aliquid nisi alias quia quod quo.",
    "subtarefas": []
  },
  {
    "id": 4,
    "empresa_id": 1,
    "tarefa": "Illo sed minus placeat consequatur sequi sunt dolorum.",
    "subtarefas": []
  },
  {
    "id": 5,
    "empresa_id": 1,
    "tarefa": "Qui recusandae amet laborum impedit consequatur.",
    "subtarefas": []
  },
  {
    "id": 6,
    "empresa_id": 1,
    "tarefa": "Nemo aut et cupiditate commodi alias.",
    "subtarefas": []
  },
  {
    "id": 7,
    "empresa_id": 1,
    "tarefa": "Veritatis dicta animi numquam quia reiciendis beatae.",
    "subtarefas": [
      {
        "id": 2,
        "5w2h_id": 7,
        "subtarefa": "Veniam fugiat ipsam nihil fugiat."
      }
    ]
  }
]
```

Nem toda tarefa tem subtarefas.

### Registrando tabela GUT
Para registrar a tabela GUT é necesssario fazer uma requisição POST na rota `api/gut/{empresa}/{hash}`, onde os parametros são id da empresa e do usuario respectivamente.

O corpo da requisição deve conter um array de até 7 dicionarios onde `pergunta_id` representa o id da pergunta que gerou a tarefa que está sendo analisada, e `gut` um vetor que representa os valores para gravidade, urgencia e tendencia.




Exemplo de corpo:

```
POST

[
  {
    "pergunta_id": 1,
    "gut" : [4, 5, 1]
  },
  {
    "pergunta_id": 2,
    "gut" : [1, 2, 3]
  },
    {
    "pergunta_id": 3,
    "gut" : [3, 3, 3]
  },
    {
    "pergunta_id": 4,
    "gut" : [2, 2, 2]
  },
    {
    "pergunta_id": 5,
    "gut" : [4, 3, 1]
  },
    {
    "pergunta_id": 6,
    "gut" : [1, 1, 1]
  },
    {
    "pergunta_id": 7,
    "gut" : [1, 1, 2]
  }
]
```

Pergunta que cada id representa:

```
[
  1 => 'O quê',
  2 => 'Por que',
  3 => 'Quem',
  4 => 'Quanto',
  5 => 'Como',
  6 => 'Quando',
  7 => 'Onde'
]
```

Resultado esperado:

```
HTTP/1.1 200 OK

{
  "sucesso": "Gut cadastrado com sucesso"
}
```


### Como criar banco de dados e popular:

Uma vez que a conexão com o banco de dados estiver configurada, o primeiro comando a ser utilizado é:

```
php artisan migrate
```
Isso irá checar se as tabelas no banco de dados estão criadas, e se não estiverem, são automaticamente feitas de acordo com as migrates.
As migrates podem ser vistas em database/migrations/.

Depois disso, basta utilizar o seguinte comando:

```
php artisan db:seed
```
Isso ira popular o banco de dados de acordo com as factories em database/factories.

Nota:
Utilize `php artisan migrate --seed` para fazer os dois comandos ao mesmo tempo

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
