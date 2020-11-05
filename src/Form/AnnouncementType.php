<?php

namespace App\Form;

use App\Entity\Announcement;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnouncementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('description',TextareaType::class)
            ->add('city',TextType::class)
            ->add('zipCode',TextType::class)
            ->add('type', ChoiceType::class,[
                'choices' => array_flip(array_map('ucfirst', Announcement::TYPES))
            ])
            ->add('price', TextType::class)
            ->add('category', ChoiceType::class, [
                'choices' => array_flip(array_map('ucfirst', Announcement::CATEGORIES))
            ])
            ->add('area', IntegerType::class)
            ->add('room', IntegerType::class)
            ->add('bedroom', IntegerType::class)
            ->add('energy', ChoiceType::class, [
                'choices' => array_flip(array_map('ucfirst', Announcement::ENERGIES))
            ])
            ->add('floor', IntegerType::class)
            ->add('images' , CollectionType::class, [
                'entry_type' => AddImageType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'attr' =>['class' => 'form_collection',]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Announcement::class,
        ]);
    }
}
