# API REST em PHP 7.4.x

API REST desenvolvida na linguagem PHP para matéria de Sistemas Distribuidos - UFPI à fim de aprender conceitos relacionadas à webservice.

Não segue nenhum modelo arquitetural de software, como MVC por exemplo. 

## Utilização



## Características e tecnologias

* PHP 7.4.9
* Modelo REST
* Orientação à Objetos(POO)
* JSON
* Autoloading de classes
* Namespaces
* PDO
* Apache 2.4.46
* Métodos GET, PUT, POST e DELETE

## Sistema

* Windows 10
* Wampserver64
* Não testado em sistema Linux.
* 
### Rotas

* **GET**

* /usuarios/login

* /usuarios/message/listar/{id}

* **DELETE**

* /usuarios/mensagens/deletar/{id}

* **POST**

* /usuarios/cadastrar
* /usuarios/mensagens/enviar
<!-- * /usuarios/mensagens/encaminhar
* /usuarios/mensagens/responder -->

* **PUT**

* Não usado

### Campos json
## Users

* id: "auto incremente"
* nome: nome do usuário

## Mensagem

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
