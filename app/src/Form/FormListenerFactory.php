<?php

namespace App\Form;

use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;

class FormListenerFactory {

    public function __construct(
        private SluggerInterface $slugger
    )
    {

    }
    
    /**
     * Créer un slug automatiquement avant de submit
     */
    public function autoSlug(string $field): callable
    {
        return function (PreSubmitEvent $event) use ($field) {
            $data = $event->getData();
            if(empty($data['slug'])) {
                // $slugger = new AsciiSlugger();
                $data['slug'] = strtolower($this->slugger->slug($data[$field]));
                $event->setData($data);
            }
        };
    }

    /**
     * Ajouter la date automatiquement
     */
    public function timestamps(): callable
    {
        return function (PostSubmitEvent $event) {
            $data = $event->getData();

            $data->setUpdatedAt(new \DateTimeImmutable());

            if(null === $data->getId()) {
                $data->setCreatedAt(new \DateTimeImmutable());
            }
        };
    }
}
