<?php
namespace Service;

use Exception;
use Util\ConstantesGenericasUtil;

// const PATH = __DIR__ . '\users.json';
class usersService
{

    // private $requestMethod;
    // private $userId;
    // private $key;

    //users
    public function getUser($userId)
    {
        $result = self::verificarUser($userId);
        if ($result == false)
        {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['User'] = json_encode($result);
        $response = str_replace("\"", "", $response);
        return $response;
    }

    private function createUserFromRequest()
    {
        // self::cadastrarUser($input['nome']);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }

    public static function verificarUser($user)
    {
        $id = '';
        try
        {
            $json = file_get_contents(__DIR__ . '\users.json');

            if ($json == false)
            {
                throw new Exception(ConstantesGenericasUtil::MSG_ERRO_AO_ABRIR_ARQUIVO);
            }
            else
            {
                $data = json_decode($json);
                // $valid = false;
                foreach ($data as $key => $value)
                {
                    if (($user == $value->id))
                    {
                        $id = $value->nome;
                        // $valid = true;
                    }
                }
                $retorno = $id;
                return $retorno;

            }
        }
        catch(Exception $e)
        {
            exit($e->getMessage());
        }

    }

    public static function isExiste($user)
    {
        $valid = false;
        try
        {
            $json = file_get_contents(__DIR__ . '\users.json');

            if (!$json)
            {
                throw new Exception(ConstantesGenericasUtil::MSG_ERRO_AO_ABRIR_ARQUIVO);
            }
            else
            {
                $data = json_decode($json);
                foreach ($data as $key => $value)
                {
                    if (($user === $value->nome))
                    {
                        $valid = true;
                    }
                    if (($user === $value->id))
                    {
                        $valid = true;
                    }
                }
                if ($valid)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
        }
        catch(Exception $e)
        {
            $e->getMessage();
        }

    }

    public function cadastrarUser($user)
    {
        try
        {
            $string = file_get_contents(__DIR__ . '\users.json');
            if (!$string)
            {
                throw new Exception(ConstantesGenericasUtil::MSG_ERRO_AO_ABRIR_ARQUIVO);
            }
            else
            {
                $json = json_decode($string, true);
                $tamArray = count($json);
                $json[$tamArray]["id"] = $tamArray+1;
                $userValid = $user[0];
                $json[$tamArray]["nome"] = $userValid;
                $fp = fopen(__DIR__ . '\users.json', 'w');
                fwrite($fp, json_encode($json, JSON_PRETTY_PRINT));
                fclose($fp);
                $json2 = json_decode($string, true);

                foreach ($json2 as $key => $value)
                {
                    if (in_array($user, $value))
                    {
                        $valid = true;

                    }
                }
                if ($valid)
                {
                    return false;
                }
                else
                {
                    return true;
                }

            }
        }
        catch(Exception $e)
        {
            echo "Exceção capturada: " . $e->getMessage();
        }

    }

    private function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode(['error' => 'Invalid input']);
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}

