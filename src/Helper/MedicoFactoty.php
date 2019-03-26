<?php
/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 25/03/2019
 * Time: 16:14
 */

namespace App\Helper;


use App\Entity\Medico;

class MedicoFactoty
{
    public function criarMedico(string $json): Medico
    {
        $dadoEmJson = json_decode($json);

        $medico = new Medico();
        $medico->crm = $dadoEmJson->crm;
        $medico->nome = $dadoEmJson->nome;

        return $medico;
    }
}