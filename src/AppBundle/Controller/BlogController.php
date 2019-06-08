<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Article;
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
     * @Route("/index", name="blog_index")
     */
    public function index()
    {
        return $this->render('AppBundle:Blog:index.html.twig');
    }

    /**
     * @Route("/ajouter-image-upload", name="blog_ajouter_image_upload")
     */
    public function ajouterImageUploadAction()
    {
        $article = new Article();
        $form = $this->createForm(new ArticleType(), $article);

        $request = $this->get('request');

        if($request->getMethod() == 'POST'){
            $form->bind($request);

            if($form->isValid()) {
                /* La ligne ci-dessous n'est plus utile désormais
                    On fait appels aux évènements Doctrine : */
                // $article->getImage()->upload();

                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();

                return $this->redirect($this->generateUrl('blog_index'));
            }
        }

        return $this->render('AppBundle:Blog:ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}