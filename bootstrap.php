<?php


error_reporting(E_ERROR);

define(HOST, 'aulas.com');
define(BANCO, 'api');
define(USUARIO, 'root');
define(SENHA, '');

define(DS, DIRECTORY_SEPARATOR);
define(DIR_APP, __DIR__);
define(DIR_PROJETO, 'api');

if(file_exists('autoload.php')){
    include 'autoload.php';
} else{
    echo 'Erro ao incluir boostrap'; exit;
}