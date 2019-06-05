<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ));
    }

    /**
     * @Route("/get_request_1", name="get_request_1")
     */
    public function getRequest1()
    {
        /* Récupérer la requête depuis le contrôleur : */
        $request = $this->getRequest();

        dump($request);

        return new Response("Hello You !");
    }

    /* Ceci dit, la bonne pratique consiste à récupérer la requête en paramètre :*/
    /**
     * @Route("/get_request_2", name="get_request_2")
     */
    public function getRequest2(Request $request)
    {
        /* Récupérer la requête depuis le contrôleur : */
        //dump($request);

        // Récupérer un paramètre (query string) de requête :
        if($request->query->get('name')) {
            $name = $request->query->get('name');
            dump($name);
        }

        return new Response("Hello You !");
    }

    /**
     * @Route("/get_cookies", name="get_cookies")
     */
    public function getCookies(Request $request)
    {
        $cookies = $request->cookies;
        dump($cookies);

        return new Response("C'est la liste des cookies courants");
    }

    /**
     * @Route("/get_server", name="get_server")
     */
    public function getServer(Request $request)
    {
        $server = $request->server;
        dump($server);

        // Récupérer un paramètre spécifique du Serveur :
        $port = $request->server->get('SERVER_PORT');
        dump($port);

        return new Response("Les infos du Serveur");
    }

    /**
     * Redirection vers une autre page :
     */
    public function redirectionPage()
    {
        return $this->redirect($this->generateUrl('get_server', [
            'port' => '8080'
        ]));
    }

}
