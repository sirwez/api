<?php
namespace Service;

use InvalidArgumentException;
use Repository\UsuariosRepository;
use Util\ConstantesGenericasUtil;

class UsuariosService
{

    public const TABELA = 'usuarios';
    public const RECURSOS_GET = ['login', 'emails'];
    public const RECURSOS_DELETE = ['deletar'];
    public const RECURSOS_POST = ['cadastrar', 'emails'];
    public const RECURSOS_PUT = ['atualizar'];

    private array $dados;
    private array $dadosCorpoRequest = [];

    private object $UsuariosRepository;
    private object $UsuariosData;

    public function __construct($dados = [])
    {
        $this->dados = $dados;
        $this->UsuariosRepository = new UsuariosRepository();
        $this->UsuariosData = new usersService();
        $this->messagesData = new messagesService();
    }

    public function setDadosCorpoRequest($dadosRequest)
    {
        $this->dadosCorpoRequest = $dadosRequest;
    }
    //post
    public function validarPost()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];
        
        if (in_array($recurso, self::RECURSOS_POST, true))
        {
            $retorno = $this->$recurso();
        }
        else
        {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }
        if ($retorno == null)
        {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }
        return $retorno;
    }
    private function cadastrar()
    {
        
        $login = [$this->dadosCorpoRequest['nome']];
        if ($login)
        {
            if ($this
                ->UsuariosData
                ->isExiste($login))
            {
                throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_LOGIN_EXISTENTE);
            }
            $idInserido = $this
                ->UsuariosData
                ->cadastrarUser($login);
            if ($idInserido)
            {
                $response['user_cadastrado'] = $idInserido;
                return $response;
            }

            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }
        throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_LOGIN_SENHA_OBRIGATORIO);
    }
    //gets
    public function validarGet()
    {

        $retorno = null;
        $recurso = $this->dados['recurso'];
        if (in_array($recurso, self::RECURSOS_GET, true) && $recurso == 'login')
        {
            $retorno = $this->dados['id'] > 0 ? $this
                ->UsuariosData
                ->getUser($this->dados['id']) : $this->$recurso();
        }
        elseif (in_array($recurso, self::RECURSOS_GET, true) && $recurso == 'emails')
        {

            $retorno = $this->dados['id'] > 0 ? $this
                ->messagesData
                ->listarMessage($this->dados['id']) : $this->$recurso();

                // $retorno = $this->dados['id'] > 0 ? $this->getOneByKey() : $this->$recurso();
        }
        else
        {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }
        if ($retorno == null)
        {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }
        return $retorno;
    }
    // private function getOneBykey()
    // {
    //     return $this
    //         ->UsuariosRepository
    //         ->getMySQL()
    //         ->getOneByKey(self::TABELA, $this->dados['id']);
    // }
    private function emails()
    {
        return $this
            ->UsuariosRepository
            ->getMySQL()
            ->getAll(self::TABELA);
    }
    private function getMessage()
    {
        return $this
            ->UsuariosRepository
            ->getMySQL()
            ->getAll(self::TABELA);
    }
    //deletes
    public function validarDelete()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];
        if (in_array($recurso, self::RECURSOS_DELETE, true))
        {
            if ($this->dados['id'] > 0)
            {
                $retorno = $this->$recurso();
            }
            else
            {
                throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_ID_OBRIGATORIO);
            }
        }
        else
        {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }
        if ($retorno == null)
        {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }
        return $retorno;
    }
    private function deletar()
    {  
     return $this->messagesData->deleteMessage($this->dados['id']);
    }

    //put
    public function validarPut()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];
        if (in_array($recurso, self::RECURSOS_PUT, true))
        {
            if ($this->dados['id'] > 0)
            {
                $retorno = $this->$recurso();
            }
            else
            {
                throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_ID_OBRIGATORIO);
            }
        }
        else
        {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }
        if ($retorno == null)
        {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }
        return $retorno;
    }

    private function atualizar()
    {
        if ($this
            ->UsuariosRepository
            ->updateUser($this->dados['id'], $this->dadosCorpoRequest) > 0)
        {
            $this
                ->UsuariosRepository
                ->getMySQL()
                ->getDb()
                ->commit();
            return ConstantesGenericasUtil::MSG_ATUALIZADO_SUCESSO;
        }

        $this
            ->UsuariosRepository
            ->getMySQL()
            ->getDb()
            ->rollback();
        throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_NAO_AFETADO);
    }
}