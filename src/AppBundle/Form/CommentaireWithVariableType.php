<?php

namespace AppBundle\Form;

use AppBundle\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CommentaireWithVariableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $varialble = 1;

        $builder
            ->add('contenu', 'textarea')
            ->add('auteur', 'text')

            ->add('article', 'entity', [
                'class' => 'AppBundle:Article',
                'property' => 'titre',

                'query_builder' => function(\AppBundle\Repository\ArticleRepository $r) use($varialble) {
                    return $r->getSelectList($varialble);
                }
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Commentaire',
        ]);
    }

    public function getName()
    {
        return 'app_bundle_articletype';
    }
}