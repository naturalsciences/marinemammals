<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use AppBundle\Entity\Repository\CgRefCodesRepository;
use AppBundle\Entity\EventStates;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class EventStatesType extends AbstractType
{
    private $doctrine;
    private $additionalOptions;

    public function __construct($doctrine,$additionalOptions)
    {
        $this->doctrine = $doctrine;
        $this->additionalOptions = $additionalOptions;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('eventDatetime', new DateTimeType(), array());
        $builder->add('eventDatetimeFlagRef', 'entity',array(
            'empty_value' => 'Select...',
            'required' => true,
            'class' => 'AppBundle:CgRefCodes',
            'property' => 'rvMeaning',
            'query_builder' => function (CgRefCodesRepository $er) {
                return $er->getRefCodesQb('DATETIME_FLAG');
            }
            //'choice_list'=>new CgRefChoiceList($this->doctrine,'DATETIME_FLAG')
        ));
        $builder->add('description', 'textarea', array(
            'required' => false
        ));
        $builder->add('observers', 'collection', array('type' => new Event2PersonsType($this->doctrine),
            'options' => array('data_class' => 'AppBundle\Entity\Event2Persons'),
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'by_reference' => false,
            'prototype_name'=>'__observers_name__'
        ));
        $builder->add('gatherers', 'collection', array('type' => new Event2PersonsType($this->doctrine),
            'options' => array('data_class' => 'AppBundle\Entity\Event2Persons'),
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'by_reference' => false,
            'prototype_name'=>'__gatherers_name__'
        ));
        $builder->add('informers', 'collection', array('type' => new Event2PersonsType($this->doctrine),
            'options' => array('data_class' => 'AppBundle\Entity\Event2Persons'),
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'by_reference' => false,
            'prototype_name'=>'__informers_name__'
        ));
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $e = $event->getData();
            $form = $event->getForm();
            $observers = $form->get('observers')->getData();
            foreach ($observers as $ob) {
                $ob->setE2pType(EventStates::OBSERVED);
                $ob->setEseSeqno($e);
            }
            $gatherers = $form->get('gatherers')->getData();
            foreach ($gatherers as $gb) {
                $gb->setE2pType(EventStates::GATHERED);
                $gb->setEseSeqno($e);
            }
            $informers = $form->get('informers')->getData();
            foreach ( $informers as $ib) {
                $ib->setE2pType(EventStates::INFORMED);
                $ib->setEseSeqno($e);
            }
        });
        $builder->add('spec2events', new Spec2EventsType($this->doctrine,$this->additionalOptions));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'data_class' => 'AppBundle\Entity\EventStates',
                'cascade_validation' => true,
                'error_mapping'=>array('eventDatetime'=>'eventDatetimeFlag','eitherNecropsyOrObservationLegal'=> 'eventDatetimeFlag'),
                'validation_groups' => array()
            ));
    }

    public function getName()
    {
        return 'eventstatestype';
    }
}