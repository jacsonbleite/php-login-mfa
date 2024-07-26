# PHP - API de Login com MFA (Autenticação Multifator)

![Status do Projeto](https://img.shields.io/badge/status-em%20desenvolvimento-yellow)

## Visão Geral

Este projeto implementa uma API de autenticação multifator (MFA) em PHP, oferecendo uma camada extra de segurança ao processo de login. Após fornecer suas credenciais, o usuário receberá um código de verificação no e-mail cadastrado, que deverá ser inserido para completar o processo de autenticação.

## Requisitos

O requisito principal é que tenha o docker instalado em seu sistema operacional.


## Funcionalidades

- **Autenticação de Usuário:** Verificação das credenciais fornecidas pelo usuário.
- **Envio de Código MFA:** Geração e envio de um código de verificação para o e-mail do usuário.
- **Validação do Código:** Verificação do código de MFA para completar o login.

## Estrutura do Projeto

Este projeto é desenvolvido utilizando o paradigma de Programação Orientada a Objetos (OOP) em PHP, que permite uma estruturação clara e modular do código. A OOP nos permite modelar o sistema em torno de objetos que encapsulam dados (atributos) e comportamentos (métodos), facilitando a organização e manutenção do código.

## Testes com PHPUnit

Para garantir a qualidade do código e a funcionalidade do sistema, implementei testes unitários robustos utilizando PHPUnit. Os testes unitários são fundamentais para identificar bugs precocemente e facilitar mudanças no sistema com confiança.



## Configuração

1. **Clone o repositório:**
    ```bash
    git clone https://github.com/seu-usuario/php-login-mfa.git
    cd php-login-mfa
    ```

2. **Configure as variáveis de ambiente:**
    dentro do diretório **html** está localizado o arquivo `.env.example` que é responsável por armazenar os dados sensíveis do projeto.
    faça uma cópia desse arquivo renomeando-o para `.env` e inserido os dados necessário para a configuração e funcionamento do prejo.
    O arquivo está servindo o `docker-compose.yml` com as configurações da base de dados e do SGDB (PgAdmin)


3. **Configuração do Docker:**
    Certifique-se de que o Docker e o Docker Compose estão instalados na sua máquina. Em seguida, execute os seguintes comandos para construir e iniciar os containers:
    ```bash
    docker-compose build
    docker-compose up -d
    ```

4. **Instale as dependências:**
    ```bash
    docker exec -it php-apache /bin/bash
    composer install
    ```



## Configuração do .htaccess

O arquivo .htaccess é o responsável por permitir que a URL seja "amigável"
_ex: http://localhost:8080/param1/param2_
No diretório **html**  é possível verifiar e realizar alterações conforme necessário


## Estrutura do Docker

O projeto utiliza Docker e Docker Compose para criar um ambiente de desenvolvimento consistente. A configuração inclui:

- **Dockerfile:** Define a imagem do Docker para o ambiente PHP com Apache.
- **docker-compose.yml:** Define os serviços e redes necessários para o projeto.


## Acessando o projeto

Para acessar o sistema
```bash
http://localhost:8080
```

Para acessar o PgAdmin
```bash
http://localhost:5050
```

## Executando os Testes

Para rodar os testes unitários, utilize o comando:
```bash
composer test
```
