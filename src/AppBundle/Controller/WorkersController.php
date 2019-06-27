<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Workers;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Repository\A0KitRepository;


/**
 * @Route("/workers")
 */
class WorkersController extends Controller
{
    /**
     *@Route("/", name="workers_homepage")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Workers');
        $workers = $repo->findAll();

        return $this->render('AppBundle:Workers:index.html.twig', [
            'workers' => $workers
        ]);
    }
}