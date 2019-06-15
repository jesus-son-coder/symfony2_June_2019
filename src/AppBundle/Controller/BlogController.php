<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Form\ArticleEditType;
use AppBundle\Form\ArticleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/blog")
 */
class BlogController extends Controller
{
    /**
     * @Route("/accueil", name="blog_accueil")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Article');
        $articles = $repo->findAll();

        // Voir le Username de l'utilsateur courant dans lo bloc de débogage :
        $this->getCurrentUser();

        return $this->render('AppBundle:Blog:index.html.twig', [
            'articles' => $articles,
        ]);
    }


    /**
     * @Route("/voir/{id}", name="blog_voir")
     */
    public function voirAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('AppBundle:Article')->find($id);

        return $this->render('AppBundle:blog:voir.html.twig', [
            'article' => $article
        ]);
    }


    /**
     * @Route("/ajouter", name="blog_ajouter")
     */
    public function ajouterIAction()
    {
        $article = new Article();
        $form = $this->createForm(new ArticleType(), $article);

        // On récupère la requête
        $request = $this->get('request');

        // On vérifie qu'elle est de type POST :
        if($request->getMethod() == 'POST'){
            // On fait le lien Requête <-> Formulaire :
            $form->bind($request);

            // On vérifie que les valeurs entrées sont correctes :
            if($form->isValid()) {
                // On enregistre notre objet $article dans la base de données :
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();

                // On définit un message flash
                $this->get('session')->getFlashBag()->add('info', 'Article bien ajouté');

                // On redirige vers la page de visualisation de l'article nouvellement créé :
                // return $this->redirect($this->generateUrl('sdzblog_voir', array('id' => $article->getId())));
                return $this->redirect($this->generateUrl('blog_accueil'));
            }
        }

        // À ce stade :
        // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
        // - Soit la requête est de type POST, mais le formulaire n'est pas valide, donc on l'affiche de nouveau
        return $this->render('AppBundle:Blog:ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/modifier/{id}", name="blog_modifier")
     */
    public function modifierAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('AppBundle:Article')->find($id);

        $form = $this->createForm(new ArticleEditType(), $article);

        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                // On enregistre l'article :
                $em->persist($article);
                $em->flush();

                // On définint un message Flash :
                $this->get('session')->getFlashBag()->add('info', 'Article bien modifié');

                return $this->redirect($this->generateUrl('blog_voir', [
                    'id' => $article->getId()
                ]));
            }
        }

        return $this->render('AppBundle:Blog:modifier.html.twig', [
            'form' => $form->createView(),
            'article' => $article
        ]);
    }


    /**
     * @Route("/supprimer/{id}", name="blog_supprimer")
     */
    public function supprimerAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('AppBundle:Article')->find($id);

        /* On crée un formulaire vide, qui ne contiendra que le champ CSFR.
            Cela permet de protéger la suppression d'article contre cette faille. */
        $form = $this->createFormBuilder()->getForm();

        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if($form->isValid()) {
                // On supprime l'article :
                $em = $this->getDoctrine()->getManager();
                $em->remove($article);
                $em->flush();

                // On définit un message flash :
                $this->get('session')->getFlashBag()->add('info', 'Article bien supprimé');

                // Puis on redirige vers l'accueil :
                return $this->redirect($this->generateUrl('blog_accueil'));
            }
        }

        /* Si la requête est en GET, on affiche une page de confirmation avant de supprimer : */
        return $this->render('AppBundle:Blog:supprimer.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);


    }


    public function getCurrentUser()
    {
        $currentUSer = $this->get('security.context')->getToken()->getUser();

        /*
         * Le Contrôleur dispose d'une méthode plus simple pour récupérer l'utilisateur courant, c'est  :
         * $this->getUSer();
         */
        dump($currentUSer->getUsername());
    }

}