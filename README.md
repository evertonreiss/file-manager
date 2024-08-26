# File Manager

![image](https://github.com/user-attachments/assets/b92701c3-f12b-4226-9bcf-991a7cb6700d)

## Front End
Para utilizar o front-end da aplicação você deve acessar o repositório [front-end-file-manager](https://github.com/evertonreiss/front-file-manager) e seguir os passos para a instalação.

## Pré-requisitos

Ferramentas necessárias para iniciar o projeto:

- [Git](https://git-scm.com/)
- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

## Instruções de Instalação da Api

Siga os passos abaixo para configurar e iniciar a aplicação:

### 1. Clone o Repositório

No terminal, execute o comando abaixo para clonar o repositório:

```bash
git clone https://github.com/evertonreiss/file-manager.git
```

### 2. Entre na Pasta do Projeto

Navegue até o diretório do projeto:

```bash
cd file-manager
```

### 3. Configure o Ambiente

Crie o arquivo \`.env\` a partir do arquivo de exemplo (Se necessário, fazer as alterações):

```bash
cp .env.example .env
```

### 4. Inicie os Contêineres com Docker Compose

Execute o comando para iniciar os contêineres em segundo plano:

```bash
docker-compose up -d
```

### 5. Acesse o Contêiner do Laravel

Entre no contêiner do Laravel:

```bash
docker-compose exec -it laravel.test bash
```

### 6. Configure a Aplicação

Dentro do terminal do contêiner, execute os seguintes comandos:

- Instale as dependências do Composer:

  ```bash
  composer install
  ```

- Gere a chave de aplicativo Laravel:

  ```bash
  php artisan key:generate
  ```

- Execute as migrações do banco de dados:

  ```bash
  php artisan migrate
  ```

- Saia do contêiner:

  ```bash
  exit
  ```

### 7. Inicie o Sail

Execute o comando para iniciar o Sail em segundo plano:
Inicie a aplicação em segundo plano usando Sail com o comando:
```bash
./vendor/bin/sail up -d
```

## Uso

A aplicação é iniciada com o comando:
```bash
./vendor/bin/sail up -d
```
 
E Finalizada com o comando: 
```bash
./vendor/bin/sail down
```
- OBS: Para utilizar o Sail do laravel é necessário estar em um ambiente wsl ou linux, no windows utilize o comando `docker-compose` no lugar do `./vendor/bin/sail` 

Para saber mais do Laravel Sail, [veja este link](https://laravel.com/docs/9.x/sail#main-content)


## Documentação da API

Para facilitar o teste das rotas da API, você pode importar a coleção do Postman disponível no seguinte link:

[Coleção Postman - file-manager](./file-manager.postman_collection.json)

1. No Postman, clique em "Importar".
2. Selecione o arquivo `file-manager.postman_collection.json` que está no repositório.
3. Clique em "Importar" para adicionar a coleção ao Postman.

### Endpoints

Aqui estão os endpoints da API disponíveis na coleção do Postman:

#### Arquivos

1. **Listagem de arquivos**
   - **Método:** GET
   - **URL:** `localhost:8000/api/v1/arquivos`
   - **Autenticação:** Bearer Token
   - **Headers:**
     - `Accept: application/json`

2. **Envio de arquivos**
   - **Método:** POST
   - **URL:** `localhost:8000/api/v1/arquivos`
   - **Autenticação:** Bearer Token
   - **Headers:**
     - `Accept: application/json`
   - **Body:**
     - `uploaded_file`: Arquivo (Form Data)
     - `description`: Descrição do arquivo
     - `is_visible`: 1
     - `is_downloadable`: 1

3. **Busca um arquivo**
   - **Método:** GET
   - **URL:** `localhost:8000/api/v1/arquivos/{id}`
   - **Autenticação:** Bearer Token
   - **Headers:**
     - `Accept: application/json`

4. **Editar arquivo**
   - **Método:** PUT
   - **URL:** `localhost:8000/api/v1/arquivos/{id}`
   - **Autenticação:** Bearer Token
   - **Headers:**
     - `Accept: application/json`
   - **Body (JSON):**
     ```json
     {
       "file_name": "Nome do Arquivo",
       "description": "Descrição do arquivo",
       "is_visible": true,
       "is_downloadable": true
     }
     ```

5. **Deletar arquivo**
   - **Método:** DELETE
   - **URL:** `localhost:8000/api/v1/arquivos/{id}`
   - **Autenticação:** Bearer Token
   - **Headers:**
     - `Accept: application/json`

6. **Download arquivo**
   - **Método:** GET
   - **URL:** `localhost:8000/api/v1/arquivos/download/{id}`
   - **Autenticação:** Bearer Token
   - **Headers:**
     - `Accept: application/json`

#### User

1. **Registrar usuário**
   - **Método:** POST
   - **URL:** `localhost:8000/api/v1/register`
   - **Headers:**
     - `Accept: application/json`
   - **Body (JSON):**
     ```json
     {
       "name": "Exemplo",
       "email": "email@exemplo.com",
       "password": "password"
     }
     ```

2. **Login**
   - **Método:** POST
   - **URL:** `localhost:8000/api/v1/login`
   - **Headers:**
     - `Accept: application/json`
   - **Body (JSON):**
     ```json
     {
       "email": "email@exemplo.com",
       "password": "password"
     }
     ```
OBS: a URL pode ser difente caso o APP_PORT do .env não seja 8000

## Contribuições
Fique a vontade para contribuir ou reportar problemas!
