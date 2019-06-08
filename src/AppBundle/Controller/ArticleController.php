<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Commentaire;
use AppBundle\Form\ArticleType;
use AppBundle\Form\ArticleType_2;
use AppBundle\Form\ArticleType_EventListener;
use AppBundle\Form\CommentaireType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Repository\A0KitRepository;

/**
 * @Route("/articles")
 */
class ArticleController extends Controller
{
    /**
     * @Route("/", name="articles_homepage")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Article');
        $articles = $repo->findAll();

        // $mes_articles = $repo->myFindAll_court_1();

        // $mon_article_1 = $repo->myFindOne(1// );

        // $mon_article_avec_auteur_et_date = $repo->findByAuteurAndDate('Stevie Wonder', '2019-02-08 10:34:56');//

        // print_r("herve"); die();
        // print_r($mon_article_avec_auteur_et_date); die();

        return new Response("Coucou !");
    }

    public function utiliserUneJointure()
    {
        /* La jointure en question se trouve dans le Repository "ArticleRepository" dans sa méthode "getArticleAvecCommentaires()" : */
        $listeArticles = $this->getDoctrine()->getManager()
                            ->getRepository('AppBundle:Article')
                            ->getArticleAvecCommentaires();

        foreach ($listeArticles as $article) {
            $commentaires[] = $article->getCommentaires();
        }

        /* Voici comment vous devez faire la plupart de vos reqêtes.
            En effet, vous aurez souvent besoin d'utiliser des entités liées entre elles,
            et faire une ou plusieurs jointure s'impose très souvent. */
    }

    /**
     * @Route("/ajouter", name="article_ajouter")
     */
    public function ajouterAction()
    {
        /*
         * Formulaire imbriqué avec une Collection de type CategorieType permettant d'ajouter une ou plusieurs Categorie à l'Article :
         */
        $article = new Article();
        $form = $this->createForm(new ArticleType(), $article);

        $request = $this->get('request');

        if($request->getMethod() == 'POST'){
            $form->bind($request);

            if($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();

                return $this->redirect($this->generateUrl('articles_homepage'));
            }
        }

        return $this->render('AppBundle:Blog:ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ajouter-categorie2", name="article_ajouter_categorie2")
     */
    public function ajouterWithCategorie2Action()
    {
        $article = new Article();
        $form = $this->createForm(new ArticleType_2(), $article);

        $request = $this->get('request');

        if($request->getMethod() == 'POST'){
            $form->bind($request);

            if($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();

                return $this->redirect($this->generateUrl('articles_homepage'));
            }
        }

        return $this->render('AppBundle:Blog:ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ajouter-article-commentaires", name="article_ajouter_article_commentaire")
     */
    public function ajouterWithCommentaireAction()
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(new CommentaireType(), $commentaire);

        $request = $this->get('request');

        if($request->getMethod() == 'POST'){
            $form->bind($request);

            if($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($commentaire);
                $em->flush();

                return $this->redirect($this->generateUrl('articles_homepage'));
            }
        }

        return $this->render('AppBundle:Blog:ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/modifier-article-eventlistener/{id}", name="article_modifier_eventlistener")
     */
    public function modifierArticleEventListenerAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('AppBundle:Article')->find($id);

        $form = $this->createForm(new ArticleType_EventListener(), $article);

        $request = $this->get('request');

        if($request->getMethod() == 'POST'){
            $form->bind($request);

            if($form->isValid()) {

                $em->persist($article);
                $em->flush();

                return $this->redirect($this->generateUrl('articles_homepage'));
            }
        }

        return $this->render('AppBundle:Blog:ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}