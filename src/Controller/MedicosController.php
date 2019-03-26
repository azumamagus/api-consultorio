<?php
/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 25/03/2019
 * Time: 10:49
 */

namespace App\Controller;



use App\Entity\Medico;
use App\Helper\MedicoFactoty;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicosController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var MedicoFactoty
     */
    private $medicoFactoty;

    public function __construct(EntityManagerInterface $entityManager, MedicoFactoty $medicoFactoty)
    {

        $this->entityManager = $entityManager;
        $this->medicoFactoty = $medicoFactoty;
    }

    /**
     * @Route("/medicos",methods={"POST"})
     */
    public function novo(Request $request) : Response
    {
        $corpoRequisicao = $request->getContent();

        $medico = $this->medicoFactoty->criarMedico($corpoRequisicao);

        $this->entityManager->persist($medico);
        $this->entityManager->flush();

        return new JsonResponse($medico);
    }

     /**
        * @Route("/medicos",methods={"GET"})
        */
    public function buscarTodos() : Response
    {
        $repositoriosDeMedicos = $this->getDoctrine()->getRepository(Medico::class);
        $medicoList = $repositoriosDeMedicos->findAll();

        return new JsonResponse($medicoList);
    }

    /**
     * @Route("/medicos/{id}",methods={"GET"})
     */
    public function buscarUm(int $id): Response
    {
        $medico = $this->buscaMedico($id);
        $codigoRetorno = is_null($medico) ? Response::HTTP_NO_CONTENT : 200;

        return new JsonResponse($medico, $codigoRetorno);
    }
    /**
     * @Route("/medicos/{id}",methods={"PUT"})
     */
    public function atualiza(int $id, Request $request) : Response
    {


        $corpoRequisicao = $request->getContent();

        $medicoEnviado = $this->medicoFactoty->criarMedico($corpoRequisicao);

        $medicoExistente = $this->buscaMedico($id);

        if(is_null($medicoExistente)){
            return new Response('',Response::HTTP_NOT_FOUND);
        }

        $medicoExistente->crm = $medicoEnviado->crm;
        $medicoExistente->nome = $medicoEnviado->nome;

        $this->entityManager->flush();

        return new JsonResponse($medicoExistente);

    }

    /**
     * @param int $id
     * @Route("/medicos/{id}"),methods={"DELETE"})
     */
    public function remove(int $id): Response
    {
        $medico = $this->buscaMedico($id);
        $this->entityManager->remove($medico);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }


    /**
     * @param int $id
     * @return Medico|object|null
     */
    public function buscaMedico(int $id)
    {
        $repositoriosDeMedicos = $this->getDoctrine()->getRepository(Medico::class);
        $medico = $repositoriosDeMedicos->find($id);
        return $medico;
    }

}