<?php

namespace App\Form;

use App\Entity\Recipe;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Sequentially;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /**
         * Créer un slug automatiquement avant de submit
         */
        $autoSlug = function (PreSubmitEvent $event): void 
        {
            $data = $event->getData();

            if(empty($data['slug'])) {
                $slugger = new AsciiSlugger();
                $data['slug'] = strtolower($slugger->slug($data['title']));
                $event->setData($data);
            }
        };

        /**
         * Ajouter la date automatiquement
         */
        $attachTimestamps = function (PostSubmitEvent $event): void 
        {
            $data = $event->getData();

            if(!($data instanceof Recipe)) {
                return;
            }

            $data->setUpdatedAt(new \DateTimeImmutable());

            if(null === $data->getId()) {
                $data->setCreatedAt(new \DateTimeImmutable());
            }
        };
        
        $builder
            ->add('title', TextType::class)
            ->add('slug', TextType::class, [
                'required' => false,
                // 'constraints' => new Sequentially ([
                //     new Length(min: 10),
                //     new Regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', message: "Ce slug n'est pas valide")
                // ])
            ])
            ->add('text')
            ->add('duration')
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer'
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, $autoSlug)
            ->addEventListener(FormEvents::POST_SUBMIT, $attachTimestamps)
        ;

        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
