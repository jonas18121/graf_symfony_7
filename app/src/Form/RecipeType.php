<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Entity\Category;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Sequentially;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RecipeType extends AbstractType
{
    public function __construct(
        private FormListenerFactory $formListenerFactory
    )
    {
        
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {        
        $builder
            ->add('title', TextType::class, [
                'empty_data' => ''
            ])
            ->add('slug', TextType::class, [
                'required' => false,
                // 'constraints' => new Sequentially ([
                //     new Length(min: 10),
                //     new Regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', message: "Ce slug n'est pas valide")
                // ])
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('text', TextareaType::class, [
                'empty_data' => ''
            ])
            ->add('duration')
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer'
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->formListenerFactory->autoSlug('title'))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->formListenerFactory->timestamps())
        ;

        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }



    // /**
        //  * Créer un slug automatiquement avant de submit
        //  */
        // $autoSlug = function (PreSubmitEvent $event): void 
        // {
        //     $data = $event->getData();

        //     if(empty($data['slug'])) {
        //         $slugger = new AsciiSlugger();
        //         $data['slug'] = strtolower($slugger->slug($data['title']));
        //         $event->setData($data);
        //     }
        // };

        // /**
        //  * Ajouter la date automatiquement
        //  */
        // $attachTimestamps = function (PostSubmitEvent $event): void 
        // {
        //     $data = $event->getData();

        //     if(!($data instanceof Category)) {
        //         return;
        //     }

        //     $data->setUpdatedAt(new \DateTimeImmutable());

        //     if(null === $data->getId()) {
        //         $data->setCreatedAt(new \DateTimeImmutable());
        //     }
        // };
}
