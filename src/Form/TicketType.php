<?php

namespace App\Form;

use App\Entity\Ticket;
use App\Form\MessageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TicketType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('flagSms', CheckboxType::class, [
                'label'    => 'SMS?',
                'required' => false,
            ])
            ->add('flagPush', CheckboxType::class, [
                'label'    => 'Push notification?',
                'required' => false,
            ])
            ->add('messages', CollectionType::class, [
                'entry_type' => MessageType::class,
                'entry_options' => ['label' => false],
            ])
            ->addEventListener(
                FormEvents::SUBMIT,
                [$this, 'onSubmit']
            )
        ;
    }

    public function onSubmit(FormEvent $event)
    {
        $user = $this->security->getUser();
        $ticket = $event->getData();
        $ticket->setCreatedBy($user);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}
