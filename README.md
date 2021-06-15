# API REST em PHP 7.4.x

API REST desenvolvida na linguagem PHP para matéria de Sistemas Distribuidos - UFPI à fim de aprender conceitos relacionadas à webservice.

Não segue nenhum modelo arquitetural de software, como MVC por exemplo. 

## Utilização



## Características e tecnologias

* PHP 7.4.9
* Modelo REST
* Orientação à Objetos(POO)
* JSON
* Apache 2.4.46
* Métodos GET, PUT, POST e DELETE

## Sistema

* Windows 10
* Wampserver64
* Não testado em sistema Linux.
### Rotas

# Post

- [ ]  Enviar mensagem
- [ ]  Responder Mensagem
- [ ]  Encaminhar mensagem

# GET

- [x]  Listar mensagens
- Lista mensagens pelo ID de quem está logado, retorna um array associativo com dois campos: Enviados, e Recebidos
- Rota: /usuarios/emails/
- [x]  Abrir mensagem
- Rota: /usuarios/emails/{id}

# DELETE

- [x]  Apagar mensagens //funcionando
- Recebe o ID Único da mensagem.
- Rota: /usuarios/deletar/{id}

# GET

- [x]  Logar  //funcionando
- recebe o ID do usuário e verifica se existe ou não (ID obrigatoriamente numérico)
- Rota: /usuarios/login/{id}

# POST

- [x]  Cadastrar  //funcionando
- Usuário cadastra seu nome e recebe um ID único gerado pelo sistema.
- Sistema de senhas não implementado
- Rota: /usuarios/cadastrar

# Outros

- [ ]  rotas
- [ ]  interface
- Observações:

## Campos json
### Users

* id: "auto-incremente"
* nome: nome do usuário

### Temp

* Armazena um ID para acessar os registros da api

### Mensagem

* id: identificação de quem está acessando a mensagem
* uniqueID: indentificador da mensagem, cada mensagem, sejá resposta, encaminhamento, ou envios tem ids próprios.
* remetente: quem envia
* destinatario: quem recebe
* assunto: assunto da mensagem
* corpo: corpo da mensagem
* lida: identificador para saber se mensagem foi aberta
* resposta: identificador se a mensagem é uma resposta
* encaminhada: identificador se a mensagem é encaminhada
### Autor

**Weslley Aquinno**
