<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Occasion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class OccasionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('shortDesc',TextareaType::class)
            ->add('longDesc', TextareaType::class)
            ->add('startDate', DateTimeType::class, [
                "date_widget" => "single_text"
            ])
            ->add('endDate', DateTimeType::class, [
                "date_widget" => "single_text"
            ])
            ->add('maxParts', IntegerType::class)
            ->add('minParts', IntegerType::class)
            ->add('category', EntityType::class, [
                'class'=> Category::class, 
                'choice_label' => 'name'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Occasion::class,
        ]);
    }
}
