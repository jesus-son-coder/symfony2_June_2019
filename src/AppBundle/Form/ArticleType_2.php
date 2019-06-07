<?php


namespace AppBundle\Form;

use AppBundle\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleType_2 extends AbstractType
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

            // Une Collection de Categories :
            ->add('categories', 'entity', [
                'class' => 'AppBundle:Categorie',
                'property' => 'nom',
                // La propriété "multiple" permet au champ de pouvoir sélectionner plusieurs valeurs simultanément :
                'multiple' => true
            ])
        ;
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