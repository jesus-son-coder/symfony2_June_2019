<?php


namespace AppBundle\Form;

use AppBundle\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', 'date')
            ->add('titre', 'text')
            ->add('contenu', 'textarea')
            ->add('auteur', 'text')
            ->add('publication', 'checkbox', [
                'required' => false
            ])
            // Ajouter un formulaire externe "ImageType" avec une Relation simple :
            ->add('image', new ImageType())

            /*
               * Rappel :
               ** - 1er argument : nom du champ, ici « categories » car c'est le nom de l'attribut
               ** - 2e argument : type du champ, ici « collection » qui est une liste de quelque chose
               ** - 3e argument : tableau d'options du champ
               */
            /*  Avec une Collection :
                ---------------------
             * ->add('categories',  'collection', array('type'         => new CategorieType(),
                                                     'allow_add'    => true,
                                                     'allow_delete' => true))*/
            ->add('categories', 'entity', array(
                'class'    => 'AppBundle:Categorie',
                'property' => 'nom',
                'multiple' => true,
                'expanded' => false
            ))
        ;

        // On récupère la factory (usine)
        $factory = $builder->getFormFactory();

        // ------------------------------------------------------------------
        // On ajoute une fonction qui va écouter l'évènement "PRE_SET_DATA" :
        // ------------------------------------------------------------------
        $builder->addEventListener(
        //Ici, on définit l'évènement qui nous intéresse
            FormEvents::PRE_SET_DATA,

            // Ici, on définit une fonction qui sera exécutée lors de l'évènement :
            function (FormEvent $event) use ($factory) {

                // On récupère l'objet $article (et ses Data), juste avant d'hydrater les champs du formulaire :
                $article = $event->getData();

                // La condition ci-dessous est importante :
                /* En effet, en cas de création d'un nouvel Article, l'objet $article est importé vide (ou null)
                    dans le formulaire, et ce cas ne nous intéresse pas...*/
                if (null === $article) {
                    // On sort de la fonction lorsque l'article vaut null :
                    return;
                }

                /* Ce qui nous intéresse, c'est le cas d'une modification d'article via le formulaire,
                    lorsque l'article récupéré n'est pas vide lors de la construction du Formulaire... */

                // Si l'article n'est pas encore publié, on ajoute le champ "publication" :
                if(false === $article->getPublication()) {
                    // Il est même possible de récupérer le Formulaire ("$event->getForm()") dans cet évènement :
                    $event->getForm()->add(
                        $factory->createNamed('publication', 'checkbox', null, [
                            'required'=>false,
                            'auto_initialize'=>false
                        ])
                    );
                } else {
                    // Sinon, on le supprime :
                    $event->getForm()->remove('publication');
                }
            }

        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Article'
        ]);
    }

    public function getName()
    {
        return 'app_bundle_articletype';
    }
}