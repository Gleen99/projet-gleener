<?php

// src/Form/PostType.php

namespace App\Form;

use App\Entity\Posts;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description'
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu'
            ])
            ->add('localisation', TextType::class, [
                'label' => 'Localisation'
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville'
            ])
            ->add('other', TextType::class, [
                'label' => 'Autre'
            ])
            ->add('annonce', ChoiceType::class, array(
                'label' => 'Type',
                'required' => true,
                'multiple' => true,
                'choice_translation_domain' => 'post',
                'choices' => array_combine(Posts::ANNONCE, Posts::ANNONCE),
                'choice_label' => function ($value, $key, $index) {
                    return $value;
                },
                'choice_attr' => function($choice, $key, $value) {
                    // Ajouter un espace après chaque choix
                    return ['class' => 'choice-item', 'style' => 'margin-right: 10px; margin-left: 10px;'];
                },
                'expanded' => true,
            ));
            
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Posts::class,
        ]);
    }
}
