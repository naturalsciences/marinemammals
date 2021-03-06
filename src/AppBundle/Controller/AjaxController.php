<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 3/03/15
 * Time: 16:56
 */

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AjaxController extends Controller
{

    private $repo;

    private function getSpecimen($id)
    {

        if ($this->repo === null) {
            $this->repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Specimens');
        }
        return $this->repo->find(intval($id));
    }

    private function getMinMaxObsDate()
    {

        if ($this->repo === null) {
            $this->repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Observations');
        }
        return $this->repo->getMinMaxObsDate();
    }


    public function scnNumberAction(Request $request, $id)
    {
        $scnNumber = $this->getSpecimen($id)->getScnNumber();
        if ($request->isXMLHttpRequest()) {
            return new JsonResponse(array('scnNumber' => $scnNumber));
        }
        return new Response('This is not ajax! The number is: ' . $scnNumber, 400);
    }

    public function scnAction(Request $request, $id)
    {
        $s = $this->getSpecimen($id);
        if ($request->isXMLHttpRequest()) {
            if ($s !== null) {
                $seqno = $s->getSeqno();
                $scnNumber = $s->getScnNumber();
                $sex = $s->getSex();
                $rbinsTag = $s->getRbinsTag();
                $collectionTag = $s->getNecropsyTag();
                $species = $s->getTxnSeqno()->getFullyQualifiedName(); //txn_seqno cannot be null
                return new JsonResponse(array('found' => true, 'seqno' => $seqno, 'scnNumber' => $scnNumber, 'sex' => $sex, 'rbinsTag' => $rbinsTag, 'collectionTag' => $collectionTag, 'species' => $species));
            } else {
                return new JsonResponse(array('found' => false));
            }
        }
        return new Response('Not ajax! Specimen is found with seqno:' . $s->getSeqno());

    }

    public function scnExistsAction(Request $request, $id)
    {
        $s = $this->getSpecimen($id);
        if ($request->isXMLHttpRequest()) {
            if ($this->getSpecimen($id) !== null) {
                return new JsonResponse(array('found' => true));
            } else {
                return new JsonResponse(array('found' => false));
            }
        }
        return new Response('This is not ajax!', 400);
    }

    public function minMaxObsDateAction(Request $request)
    {
        if ($request->isXMLHttpRequest()) {
            return new JsonResponse($this->getMinMaxObsDate());
        }
        return new Response('This is not ajax!', 400);
    }
}