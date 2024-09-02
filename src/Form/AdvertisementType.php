<?php

namespace App\Form;

use App\Entity\Advertisement;
use App\Entity\Category;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvertisementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['empty_data' => '', 'attr' => ['minlength' => 10, 'maxlength' => 100]])
            ->add('description', TextType::class, ['empty_data' => '', 'attr' => ['minlength' => 20, 'maxlength' => 1000]])
            ->add('price', IntegerType::class, ['empty_data' => '', 'attr' => ['positiveornull' => '']])
            ->add('location', TextType::class, ['empty_data' => '', 'attr' => ['minlength' => 2, 'maxlength' => 100]])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Advertisement::class,
        ]);
    }
}
