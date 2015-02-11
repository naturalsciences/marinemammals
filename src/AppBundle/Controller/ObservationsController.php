<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Observations;
use AppBundle\Entity\ObservationValues;
use AppBundle\Entity\EventStates;
use AppBundle\Entity\Event2Persons;
use AppBundle\Entity\Spec2Events;
use AppBundle\Entity\Specimens;
use AppBundle\Entity\SpecimenValues;
use AppBundle\Entity\EntityValues;
use AppBundle\Entity\ValueAssignable;
use AppBundle\Form\ObservationsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ObservationsController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $observations = $em->getRepository('AppBundle:Observations')
            ->getCompleteObservation();

        return $this->render('AppBundle:Page:list-observations.html.twig', array(
            'observations' => $observations,
        ));
    }

    public function newAction()
    {
        $observation = $this->prepareObservation();
        $form = $this->createForm(new ObservationsType($this->getDoctrine()), $observation);
        return $this->render('AppBundle:Page:add-observations-specimens.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function createAction(Request $request)
    {
        $observation=$this->prepareObservation();
        $event=$observation->getEseSeqno();
        $s2e=$event->getSpec2Events();

        $form = $this->createForm(new ObservationsType($this->getDoctrine()), $observation);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()
                ->getEntityManager();
            $em->persist($event);
            $em->persist($observation);
            $em->persist($s2e);
            foreach ($observation->getValues() as $ov) {
                $this->persistOrRemoveEntityValue($ov, $observation);
            }
            foreach ($s2e->getValues() as $sv) {
                $this->persistOrRemoveEntityValue($sv, $s2e);
            }
            foreach ($event->getEvent2Persons() as $e2p) {
                $this->persistOrRemoveEvent2Persons($e2p,$event);
            }
            $em->flush();

            return $this->redirect($request->getUri());
        }
      /*  else{
            \Doctrine\Common\Util\Debug::dump($form->getErrorsAsString()); //TODO
        }*/

        return $this->render('AppBundle:Page:add-observations-specimens.html.twig', array(
            'form' => $form->createView()
        ));
    }

    //do this in a handler: CAN IT?
    private function persistOrRemoveEntityValue(EntityValues $ev, ValueAssignable $va)
    {
        $em = $this->getDoctrine()
            ->getEntityManager();
        if ($ev->getValue() === '' or $ev->getValue() === null) { //TODO empty values should be allowed!
            $va->removeValue($ev);
            $em->remove($ev);
        } else {
            $em->persist($ev);
        }
    }

    //do this in a handler: CAN IT?
    private function persistOrRemoveEvent2Persons(Event2Persons $e2p, EventStates $e)
    {
        $em = $this->getDoctrine()
            ->getEntityManager();
        if ($e2p->getPsnSeqno() === null) {
            $e->removeEvent2Persons($e2p);
            $em->remove($e2p);
        } else {
            $em->persist($e2p);
        }
    }

    private function prepareObservation(){
        $observation = new Observations();
        $event = new EventStates();
        $observation->setEseSeqno($event);

        $event2Persons1 = new Event2Persons();
        $event2Persons1->setEseSeqno($event);
        $event2Persons1->setE2pType(EventStates::OBSERVED);
        $event->getEvent2Persons()->add($event2Persons1);

        $event2Persons2 = new Event2Persons();
        $event2Persons2->setEseSeqno($event);
        $event2Persons2->setE2pType(EventStates::GATHERED);
        $event->getEvent2Persons()->add($event2Persons2);

        $this->instantiateObservationValues('Wind direction', $observation,true,false);
        $this->instantiateObservationValues('Wind speed', $observation,true,false);
        $this->instantiateObservationValues('Seastate', $observation,true,false);

        $s2e = new Spec2Events();
        //$specimen=new Specimens();
        $event->setSpec2Events($s2e);
        //$s2e->setEseSeqno($event); //TODO: check if this can be deleted. shoudle be now that this is truly bidirectional
        //$s2e->setScnSeqno($specimen);

        $this->instantiateSpecimenValues('Before intervention', $s2e,true,true);
        $this->instantiateSpecimenValues('During intervention', $s2e,true,true);
        $this->instantiateSpecimenValues('Collection', $s2e,true,true);
        $this->instantiateSpecimenValues('Decomposition Code', $s2e,true,true);

        $this->instantiateSpecimenValues('Body length', $s2e,true,false);
        $this->instantiateSpecimenValues('Body weight', $s2e,true,false);
        $this->instantiateSpecimenValues('Age', $s2e,true,false);
        $this->instantiateSpecimenValues('Nutritional Status', $s2e,true,false);

        $this->instantiateSpecimenValues('Fresh external lesions::Fresh bite marks', $s2e, false, true);
        $this->instantiateSpecimenValues('Fresh external lesions::Opened abdomen', $s2e, false, true);
        $this->instantiateSpecimenValues('Fresh external lesions::Stabbing wound', $s2e, false, true);
        $this->instantiateSpecimenValues('Fresh external lesions::Parallel cuts', $s2e, false, true);
        $this->instantiateSpecimenValues('Fresh external lesions::Broken bones', $s2e, false, true);
        $this->instantiateSpecimenValues('Fresh external lesions::Hypothema', $s2e, false, true);
        $this->instantiateSpecimenValues('Fresh external lesions::Fin amputation', $s2e, false, true);
        $this->instantiateSpecimenValues('Fresh external lesions::Encircling lesion', $s2e, false, true);
        $this->instantiateSpecimenValues('Fresh external lesions::Other fresh external lesions', $s2e, false, true);
        $this->instantiateSpecimenValues('Fresh external lesions::Line/net impressions or cuts::Tail', $s2e, false, true);
        $this->instantiateSpecimenValues('Fresh external lesions::Line/net impressions or cuts::Pectoral fin', $s2e, false, true);
        $this->instantiateSpecimenValues('Fresh external lesions::Line/net impressions or cuts::Snout', $s2e, false, true);
        $this->instantiateSpecimenValues('Fresh external lesions::Line/net impressions or cuts::Mouth', $s2e, false, true);
        $this->instantiateSpecimenValues('Fresh external lesions::Scavenger traces::Picks', $s2e, false, true);
        $this->instantiateSpecimenValues('Fresh external lesions::Scavenger traces::Bites', $s2e, false, true);
        $this->instantiateSpecimenValues('Healing/healed lesions::Bites', $s2e, false, true);
        $this->instantiateSpecimenValues('Healing/healed lesions::Pox-like lesions', $s2e, false, true);
        $this->instantiateSpecimenValues('Healing/healed lesions::Open warts', $s2e, false, true);
        $this->instantiateSpecimenValues('Healing/healed lesions::Cuts', $s2e, false, true);
        $this->instantiateSpecimenValues('Healing/healed lesions::Line/net impressions', $s2e, false, true);
        $this->instantiateSpecimenValues('Fishing activities::Static gear on beach nearby', $s2e, false, true);
        $this->instantiateSpecimenValues('Fishing activities::Static gear at sea nearby', $s2e, false, true);
        $this->instantiateSpecimenValues('Other characteristics::External parasites', $s2e, false, true);
        $this->instantiateSpecimenValues('Other characteristics::Froth from airways', $s2e, false, true);
        $this->instantiateSpecimenValues('Other characteristics::Fishy smell', $s2e, false, true);
        $this->instantiateSpecimenValues('Other characteristics::Prey remains in mouth', $s2e, false, true);
        $this->instantiateSpecimenValues('Other characteristics::Remains of nets, ropes, plastic, etc.', $s2e, false, true);
        $this->instantiateSpecimenValues('Other characteristics::Oil remains on skin', $s2e, false, true);
        $this->instantiateSpecimenValues('Nutritional condition', $s2e, false, true);
        $this->instantiateSpecimenValues('Stomach Content', $s2e, false, true);
        $this->instantiateSpecimenValues('Other remarks', $s2e, false, false);

        $this->instantiateSpecimenValues('Cause of death::Natural', $s2e, false, true);
        $this->instantiateSpecimenValues('Cause of death::Bycatch', $s2e, false, true);
        $this->instantiateSpecimenValues('Cause of death::Ship strike', $s2e, false, true);
        $this->instantiateSpecimenValues('Cause of death::Predation', $s2e, false, true);
        $this->instantiateSpecimenValues('Cause of death::Other', $s2e, false, true);
        $this->instantiateSpecimenValues('Cause of death::Unknown', $s2e, false, true);

        $this->instantiateSpecimenValues('Bycatch activity::Professional gear', $s2e, false, false);
        $this->instantiateSpecimenValues('Bycatch activity::Recreational gear', $s2e, false, false);
        $this->instantiateSpecimenValues('Bycatch activity::Angling', $s2e, false, false);
        $this->instantiateSpecimenValues('Bycatch activity::Unknown fishing gear', $s2e, false, false);

        return $observation;
    }

    private function instantiateObservationValues($pmName, &$observation, $mustBeFlagged,$mustBeCompleted)
    {
        $em = $this->getDoctrine()->getManager();
        $pm = $em->getRepository("AppBundle:ParameterMethods")->getParameterMethodByName($pmName);
        $ov = new ObservationValues();
        $ov->setPmdSeqno($pm);
        $ov->setEseSeqno($observation);
        $ov->setValueFlagRequired($mustBeFlagged);
        $ov->setValueRequired($mustBeCompleted);
        return $ov;
    }

    private function instantiateSpecimenValues($pmName, &$s2e, $mustBeFlagged,$mustBeCompleted)
    {
        $em = $this->getDoctrine()->getManager();
        $pm = $em->getRepository("AppBundle:ParameterMethods")->getParameterMethodByName($pmName);
        $sv = new SpecimenValues();
        $sv->setPmdSeqno($pm);
        $sv->setS2eScnSeqno($s2e);
        $sv->setValueFlagRequired($mustBeFlagged);
        $sv->setValueRequired($mustBeCompleted);
        return $sv;
    }

    public function editAction($id)
    {
        $observation = $this->loadObservation($id);
        $form = $this->createForm(new ObservationsType($this->getDoctrine()), $observation);
        return $this->render('AppBundle:Page:add-observations-specimens.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function updateAction(Request $request, $id)
    {
        $observation = $this->loadObservation($id);
        $form = $this->createForm(new ObservationsType($this->getDoctrine()), $observation);
        $form->handleRequest($request);
        if ($form->isValid()) {
               // updating stuff here ...
            return $this->redirect($this->generateUrl('some_edit_route', array('id' => $id)));
        }
        return array('observation' => $observation);
    }

    private function loadObservation($id)
    {
        $observation = $this->getDoctrine()->getRepository('AppBundle:Observations')->find($id);
        //$event=$this->getDoctrine()->getRepository('AppBundle:EventStates')->find($id);
        //$s2e=$this->getDoctrine()->getRepository('AppBundle:Spec2Events')->findByEseSeqno($event);
        //$event->setSpec2Events($s2e);
        //$specimen=$this->getDoctrine()->getRepository('AppBundle:Specimens')->findBySeqno(\AppBundle\Entity\EventStates $scnSeqno)

        if (!$observation) {
            throw $this->createNotFoundException(sprintf('omg this observation could not be loaded: %s', $id));
        }
        return $observation;
    }


    public function testAction()
    {
        $t=new \AppBundle\Tests\Entity\ObservationValidationTest();
        $to=$t->generateTestObject();

        $validator = $this->get('validator');
        $errors = $validator->validate($to['sv']);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new \Symfony\Component\HttpFoundation\Response($errorsString);
        }
        return new \Symfony\Component\HttpFoundation\Response('The observation is valid! Yes!');
    }
}
