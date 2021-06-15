<?php
namespace Service;

use Exception;
use InvalidArgumentException;
use Util\ConstantesGenericasUtil;
// const PATH = __DIR__ . '\messages.json';
class messagesService
{

    private static function verificaMessage()
    {
        try
        {
            // extrai a informação do ficheiro
            $string = file_get_contents(__DIR__ . '\messages.json');
            if (!$string)
            {
                throw new Exception(ConstantesGenericasUtil::MSG_ERRO_AO_ABRIR_ARQUIVO);
            }
            else
            {
                // faz o decode o json para uma variavel php que fica em array
                $json = json_decode($string, true);
                $tamArray = count($json);
                $retorno = [$json, $tamArray];
                return $retorno;
            }
        }
        catch(Exception $e)
        {
            echo "Exceção capturada: " . $e->getMessage();
        }
    }

    public static function send_Message($id, $remetente, $destinatario, $assunto, $corpo)
    {
        //Vou usar essas coisas na principal
        // $user = new PersonController();
        try
        {
            //$user->isExiste($destinatario) && $user->isExiste($remetente)
            if (true)
            {
                $responde = self::verificaMessage();
                $responde[0][$responde[1]]["id"] = $id;
                $responde[0][$responde[1]]["remetente"] = $remetente;
                $responde[0][$responde[1]]["destinatario"] = $destinatario;
                $responde[0][$responde[1]]["assunto"] = $assunto;
                $responde[0][$responde[1]]["corpo"] = $corpo;
                // $responde[0][$responde[1]]["resposta"] = false;
                // $responde[0][$responde[1]]["encaminhar"] = false;
                $responde[0][$responde[1]]["lida"] = false;
                // abre o ficheiro em modo de escrita
                $fp = fopen(__DIR__ . '\messages.json', 'w');
                // escreve no ficheiro em json
                fwrite($fp, json_encode($responde[0], JSON_PRETTY_PRINT));
                // fecha o ficheiro
                fclose($fp);

                $tam = self::verificaMessage();
                if ($tam[1] > $responde[1])
                {
                    return ConstantesGenericasUtil::TIPO_SUCESSO;
                }
                else
                {
                    return ConstantesGenericasUtil::TIPO_ERRO;
                }

            }
            else
            {
                $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
                $response['body'] = 'Destinatário ou Remetente não existem';
                return $response;
            }
        }
        catch(Exception $e)
        {
            $e->getMessage();
        }

    }

    public function listarMessage($id)
    {
        $UsuariosData = new usersService();
        $valid = false;
        $json = file_get_contents(__DIR__ . '\users.json');
        $data = json_decode($json);

        foreach ($data as $key => $value)
        {
            if (($id == $value->id))
            {
                $valid = true;
            }
        }

        if ($valid)
        {

            $allMessages[][] = []; //0 é enviadas, e 1 recebidas
            try
            {
                $string = file_get_contents(__DIR__ . '\messages.json');
                if (!$string)
                {
                    throw new Exception(ConstantesGenericasUtil::MSG_ERRO_AO_ABRIR_ARQUIVO);
                }
                else
                {
                    $json = json_decode($string);
                    $contEnv = 0;
                    $contRec = 0;
                    foreach ($json as $key => $value)
                    {
                        if ($id == $value->id)
                        {
                            if ($UsuariosData->verificarUser($id) == $value->remetente)
                            {
                                $allMessages['Enviadas'][$contEnv]['remetente'] = $value->remetente;
                                $allMessages['Enviadas'][$contEnv]['destinatario'] = $value->destinatario;
                                $allMessages['Enviadas'][$contEnv]["assunto"] = $value->assunto;
                                $allMessages['Enviadas'][$contEnv]["corpo"] = $value->corpo;

                                $contEnv++;
                            }
                            if (!($UsuariosData->verificarUser($id) == $value->remetente))
                            {
                                $allMessages['Recebidas'][$contRec]['remetente'] = $value->remetente;
                                $allMessages['Recebidas'][$contRec]['destinatario'] = $value->destinatario;
                                $allMessages['Recebidas'][$contRec]["assunto"] = $value->assunto;
                                $allMessages['Recebidas'][$contRec]["corpo"] = $value->corpo;

                                $contRec++;
                            }
                        }
                    }
                    unset($allMessages[0]);
                    if (empty($allMessages))
                    {
                        throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERR0_NENHUMA_MSG_ENCONTRADA);
                    }
                    else
                    {
                        return $allMessages;
                    }

                }
            }
            catch(Exception $e)
            {
                echo "Exceção capturada: " . $e->getMessage();
            }

        }
        else
        {
            $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
            $response['body'] = null;
            return $response;
        }
    }

    public function getMessage($id, $remetente, $destinatario, $assunto, $corpo)
    {
        $oneMessages = []; //retorna uma
        $allMessages = []; //seta true para lido no .json
        try
        {
            // extrai a informação do ficheiro
            $string = file_get_contents(__DIR__ . '\messages.json');
            if (!$string)
            {
                throw new Exception(ConstantesGenericasUtil::MSG_ERRO_AO_ABRIR_ARQUIVO);
            }
            else
            {
                // faz o decode o json para uma variavel php que fica em array
                $json = json_decode($string);
                $cont = 0;
                foreach ($json as $key => $value)
                {
                    if (($id == $value->id) && ($remetente == $value->remetente) && ($destinatario == $value->destinatario) && ($assunto == $value->assunto) && ($corpo == $value->corpo))
                    {
                        $oneMessages[$cont]['id'] = $value->id;
                        $oneMessages[$cont]['remetente'] = $value->remetente;
                        $oneMessages[$cont]['destinatario'] = $value->destinatario;
                        $oneMessages[$cont]["assunto"] = $value->assunto;
                        $oneMessages[$cont]["corpo"] = $value->corpo;
                        $cont++;
                    }

                }
                if (empty($oneMessages))
                {
                    throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERR0_NENHUMA_MSG_ENCONTRADA);
                }
                else
                {

                    $json = json_decode($string);
                    $cont = 0;
                    foreach ($json as $key => $value)
                    {
                        if (($id == $value->id) && $destinatario == $value->destinatario && $assunto == $value->assunto && $corpo == $value->corpo)
                        {
                            $allMessages[$cont]['id'] = $value->id;
                            $allMessages[$cont]['remetente'] = $value->remetente;
                            $allMessages[$cont]['destinatario'] = $value->destinatario;
                            $allMessages[$cont]["assunto"] = $value->assunto;
                            $allMessages[$cont]["corpo"] = $value->corpo;
                            // $allMessages[$cont]["resposta"] = $value->resposta;
                            // $allMessages[$cont]["encaminhar"] = $value->encaminhar;
                            $allMessages[$cont]["lida"] = true;

                        }
                        else
                        {
                            $allMessages[$cont]['id'] = $value->id;
                            $allMessages[$cont]['remetente'] = $value->remetente;
                            $allMessages[$cont]['destinatario'] = $value->destinatario;
                            $allMessages[$cont]["assunto"] = $value->assunto;
                            $allMessages[$cont]["corpo"] = $value->corpo;
                            // $allMessages[$cont]["resposta"] = $value->resposta;
                            // $allMessages[$cont]["encaminhar"] = $value->encaminhar;
                            $allMessages[$cont]["lida"] = $value->lido;

                        }
                        $cont++;

                    }
                    $fp = fopen(__DIR__ . '\messages.json', 'w');
                    // escreve no ficheiro em json
                    fwrite($fp, json_encode($allMessages, JSON_PRETTY_PRINT));
                    // fecha o ficheiro
                    fclose($fp);
                    return $oneMessages;
                }
                // return $retorno;
                
            }
        }
        catch(Exception $e)
        {
            echo "Exceção capturada: " . $e->getMessage();
        }

    }

    public function encaminharMessage($id, $remetente, $novoDestinatario, $assunto, $corpo)
    {
        $encaminhadaMessages = [];
        try
        {
            // extrai a informação do ficheiro
            $string = file_get_contents(__DIR__ . '\messages.json');
            if (!$string)
            {
                throw new Exception(ConstantesGenericasUtil::MSG_ERRO_AO_ABRIR_ARQUIVO);
            }
            else
            {
                // faz o decode o json para uma variavel php que fica em array
                $json = json_decode($string);
                //$cont = 0; verificar depois se esse cont faz diferença
                foreach ($json as $key => $value)
                {
                    if (($id == $value->id) && ($remetente == $value->remetente) && ($assunto == $value->assunto) && ($corpo == $value->corpo))
                    {
                        $encaminhadaMessages[0]['id'] = $value->id;
                        $encaminhadaMessages[0]['remetente'] = $value->remetente;
                        $encaminhadaMessages[0]['destinatario'] = $novoDestinatario;
                        $encaminhadaMessages[0]["assunto"] = $value->assunto;
                        $encaminhadaMessages[0]["corpo"] = $value->corpo;

                    }
                    //$cont++;
                    
                }
                if (empty($encaminhadaMessages))
                {
                    throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERR0_NENHUMA_MSG_ENCONTRADA);
                }
                else
                {
                    $responde = self::verificaMessage();
                    $responde[0][$responde[1]]["id"] = $encaminhadaMessages[0]['id'];
                    $responde[0][$responde[1]]["remetente"] = $encaminhadaMessages[0]['remetente'];
                    $responde[0][$responde[1]]["destinatario"] = $encaminhadaMessages[0]['destinatario'];
                    $responde[0][$responde[1]]["assunto"] = $encaminhadaMessages[0]['assunto'];
                    $responde[0][$responde[1]]["corpo"] = $encaminhadaMessages[0]['corpo'];
                    // $responde[0][$responde[1]]["resposta"] = false;
                    // $responde[0][$responde[1]]["encaminhada"] = true;
                    $responde[0][$responde[1]]["lida"] = false;
                    // abre o ficheiro em modo de escrita
                    $fp = fopen(__DIR__ . '\messages.json', 'w');
                    // escreve no ficheiro em json
                    fwrite($fp, json_encode($responde[0], JSON_PRETTY_PRINT));
                    // fecha o ficheiro
                    fclose($fp);

                    $tam = self::verificaMessage();
                    if ($tam[1] > $responde[1])
                    {
                        $response = array(
                            'status' => 1,
                            'status_message' => 'Mensagem encaminhada com sucesso.'
                        );
                    }
                    else
                    {
                        $response = array(
                            'status' => 0,
                            'status_message' => 'Falha ao encaminhar mensagem.'
                        );
                    }

                    return $encaminhadaMessages;
                }
                // return $retorno;
                
            }
        }
        catch(Exception $e)
        {
            echo "Exceção capturada: " . $e->getMessage();
        }

    }

    public function deleteMessage($idUnique)
    {
        $allMessages = [];
        try
        {
            $string = file_get_contents(__DIR__ . '\messages.json');
            if (!$string)
            {
                throw new Exception(ConstantesGenericasUtil::MSG_ERRO_AO_ABRIR_ARQUIVO);
            }
            else
            {
                $json = json_decode($string);
                $tamAntes = self::verificaMessage();
                $cont = 0;
                foreach ($json as $key => $value)
                {
                    if (($idUnique == $value->uniqueID))
                    {
                        unset($allMessages[$cont]);
                    }
                    else
                    {
                        $allMessages[$cont]['id'] = $value->id;
                        $allMessages[$cont]['uniqueID'] = $value->uniqueID;
                        $allMessages[$cont]['remetente'] = $value->remetente;
                        $allMessages[$cont]['destinatario'] = $value->destinatario;
                        $allMessages[$cont]['assunto'] = $value->assunto;
                        $allMessages[$cont]['corpo'] = $value->corpo;
                        $allMessages[$cont]['lida'] = $value->lida;
                        $allMessages[$cont]['resposta'] = $value->resposta;
                        $allMessages[$cont]['encaminhada'] = $value->encaminhada;
                        $cont++;
                    }

                }
                $fp = fopen(__DIR__ . '\messages.json', 'w');
                fwrite($fp, json_encode($allMessages, JSON_PRETTY_PRINT));
                fclose($fp);
                
                $json2 = json_decode($string);
                $isDeleted = false;
                foreach ($json2 as $key => $value)
                {
                    if (($idUnique == $value->uniqueID))
                    {
                        $isDeleted = true;
                    }
                    else
                    {
                        continue;
                    }

                }
                if ($isDeleted)
                {
                    return ConstantesGenericasUtil::MSG_DELETADO_SUCESSO;
                }
                else
                {
                    return ConstantesGenericasUtil::MSG_ERRO_GENERICO;
                }

            }
        }
        catch(Exception $e)
        {
            echo "Exceção capturada: " . $e->getMessage();
        }
    }

    public function responderMessage($id, $remetente, $destinatario, $assunto, $corpo)
    {
        $msgResp = self::send_Message($id, $destinatario, $remetente, $assunto, $corpo);
        return $msgResp;
    }

}

