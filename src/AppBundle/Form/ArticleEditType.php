<?php


namespace AppBundle\Form;

use AppBundle\Form\ArticleType;
use AppBundle\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/* On hérite de "ArtileType" : */
class ArticleEditType extends ArticleType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // On fait appel à la méthode "buildform" du parent, qui va ajouter tous les champs à Builder :
        parent::buildForm($builder, $options);

        // On supprime le champ qu'on ne veut pas dans le formulaire d'édition :
        $builder->remove('date');
    }


    public function getName()
    {
        /* On modifie le "name" car les deux formulaires doivent avoir un nom différent : */
        return 'app_bundle_articleedittype';
    }
}