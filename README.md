# Leo - Aprendizado descomplicado, do seu jeito!

## Descrição do Projeto
Leo é uma plataforma de cursos online que permite criar, gerenciar e participar de cursos de forma simples e intuitiva.

## Funcionalidades do Projeto
- **Cadastro e login de usuários** (alunos e administradores)
- **Criação, edição e exclusão de cursos**
- **Upload de imagens para os cursos**
- **Cadastro e exibição de slides no slideshow da página inicial**
- **Modal de cursos exibido no primeiro acesso do usuário**
- **Interface responsiva** para desktop, tablet e celular
- **Sistema de rotas e arquitetura em camadas** (MVC + Services + Repositories)

## Tecnologias e Linguagens
As principais tecnologias utilizadas no projeto são:

- **PHP 8.4**: Linguagem principal do backend
- **MySQL**: Banco de dados relacional para persistência das informações
- **HTML5**: Estrutura semântica das páginas
- **CSS (SASS)**: Estilização do layout com pré-processador para facilitar manutenção
- **JavaScript (Vanilla)**: Interatividade no frontend (modal, slideshow, etc.)
- **Apache (.htaccess)**: Gerenciamento de rotas amigáveis (URL rewriting)
- **PDO (PHP Data Objects)**: Camada de acesso ao banco de dados com prepared statements
- **Arquitetura em camadas (MVC + Services + Repositories)**: Separação clara de responsabilidades e manutenibilidade do código

## Pré-requisitos e Instalação

Antes de iniciar o projeto, certifique-se de ter os seguintes pré-requisitos instalados:

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

💡 **Verifique se o serviço do Docker está em execução antes de continuar.**

###  Passos para rodar o projeto

**1. Clone este repositório:**
   ```bash
   git clone https://github.com/fariaslima/desafio_revvo.git
   cd desafio_revvo
```
**2. Crie e suba os containers:**
   ```bash
   docker compose up -d --build
```
**3. Configure a conexão com o banco de dados:**

As credenciais padrão do MySQL estão no docker-compose.yml:
```bash
HOST: mysql
PORT: 3306
DATABASE: db_revvo_2025
USERNAME: root
PASSWORD: root
```

Abra o arquivo config/database.php e atualize as informações de conexão para refletirem esses mesmos valores.

**4. Rodar as migrations para configurar o banco de dados:**
- Após a instalação do container, é necessário rodar as migrations para criar a estrutura do banco de dados, cadastrar os usuários iniciais e os cursos.
- Rode o comando abaixo para realizar a migração:

```bash
php migrations/migrations.php
```
- Usuário e senha padrão:
    - Admin: admin@example.com | admin123
    - Usuário: user@example.com | user123

**5. Acesse a aplicação no navegador:**

  Abra o navegador e acesse: http://localhost:8080/login
## Autores

- Paulo Lima (fariaslima@gmail.com)
- (21) 979281666
- [@fariaslima](https://www.github.com/fariaslima)

