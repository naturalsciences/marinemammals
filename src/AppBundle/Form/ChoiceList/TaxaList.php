<?php

namespace AppBundle\Form\ChoiceList;
use Symfony\Component\Form\Extension\Core\ChoiceList\LazyChoiceList;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;

class TaxaList extends LazyChoiceList
{
    private $doctrine;

    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    protected function loadChoiceList()
    {
        $objects = $this
            ->doctrine->getRepository('AppBundle:Taxa')->getAll();
        $values=array();
        $labels=array();
        foreach ($objects as $o) {
            $value = $o->getCanonicalName();
            $label = $o->getCanonicalName();
            array_push($values,$value);
            array_push($labels,$label);
        }
        $cl=new ChoiceList($values,$labels);
        return $cl;
    }
}