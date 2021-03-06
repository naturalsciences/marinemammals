<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use \DateTime;

/**
 * EventStates
 *
 * @ORM\Table(name="EVENT_STATES", indexes={@ORM\Index(name="idx_cln_seqno", columns={"CLN_SEQNO"})})
 * @ORM\Entity
 */
class EventStates
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CRE_DAT", type="datetime", nullable=true)
     */
    private $creDat;

    /**
     * @var string
     *
     * @ORM\Column(name="CRE_USER", type="string", length=30, nullable=true)
     */
    private $creUser;

    /**
     * @var string
     *
     * @ORM\Column(name="DESCRIPTION", type="string", length=4000, nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="EVENT_DATETIME", type="datetime", nullable=false)
     */
    private $eventDatetime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="MOD_DAT", type="datetime", nullable=true)
     */
    private $modDat;

    /**
     * @var string
     *
     * @ORM\Column(name="MOD_USER", type="string", length=30, nullable=true)
     */
    private $modUser;

    /**
     * @var integer
     *
     * @ORM\Column(name="SEQNO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="EVENT_STATES_SEQ", allocationSize=1, initialValue=1)
     */
    private $seqno;

    /**
     * @var \AppBundle\Entity\Observations
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Observations", mappedBy="eseSeqno")
     */
    private $observation;

    /**
     * @var \AppBundle\Entity\Necropsies
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Necropsies", mappedBy="eseSeqno", fetch="LAZY")
     */
    private $necropsy;

    /**
     * @var \AppBundle\Entity\ContainerLocalizations
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ContainerLocalizations")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="CLN_SEQNO", referencedColumnName="SEQNO")
     * })
     */
    private $clnSeqno;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Medias", mappedBy="eseSeqno")
     */
    private $mdaSeqno;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Spec2Events", mappedBy="eseSeqno")
     **/
    private $spec2events;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Event2Persons", mappedBy="eseSeqno")
     */
    private $event2Persons;

    /**
     * @var \AppBundle\Entity\CgRefCodes
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CgRefCodes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="EVENT_DATETIME_FLAG_REF", referencedColumnName="SEQNO")
     * })
     */
    private $eventDatetimeFlagRef;

    const COLLECTED = 'CB';

    const OBSERVED = 'OB';

    const INFORMED = 'IB';

    const EXAMINED ='EB';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->event2Persons = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMdaSeqno()
    {
        return $this->mdaSeqno;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $mdaSeqno
     * @return EventStates
     */
    public function setMdaSeqno($mdaSeqno)
    {
        $this->mdaSeqno = $mdaSeqno;
        return $this;
    }


    /**
     * Set creDat
     *
     * @param \DateTime $creDat
     * @return EventStates
     */
    public function setCreDat($creDat)
    {
        $this->creDat = $creDat;

        return $this;
    }

    /**
     * Get creDat
     *
     * @return \DateTime
     */
    public function getCreDat()
    {
        return $this->creDat;
    }

    /**
     * Set creUser
     *
     * @param string $creUser
     * @return EventStates
     */
    public function setCreUser($creUser)
    {
        $this->creUser = $creUser;

        return $this;
    }

    /**
     * Get creUser
     *
     * @return string
     */
    public function getCreUser()
    {
        return $this->creUser;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return EventStates
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set eventDatetime
     *
     * @param \DateTime $eventDatetime
     * @return EventStates
     */
    public function setEventDatetime($eventDatetime)
    {
        $this->eventDatetime = $eventDatetime;

        return $this;
    }

    /**
     * Get eventDatetime
     *
     * @return \DateTime
     */
    public function getEventDatetime()
    {
        return $this->eventDatetime;
    }

    /**
     * Set modDat
     *
     * @param \DateTime $modDat
     * @return EventStates
     */
    public function setModDat($modDat)
    {
        $this->modDat = $modDat;

        return $this;
    }

    /**
     * Get modDat
     *
     * @return \DateTime
     */
    public function getModDat()
    {
        return $this->modDat;
    }

    /**
     * Set modUser
     *
     * @param string $modUser
     * @return EventStates
     */
    public function setModUser($modUser)
    {
        $this->modUser = $modUser;

        return $this;
    }

    /**
     * Get modUser
     *
     * @return string
     */
    public function getModUser()
    {
        return $this->modUser;
    }

    /**
     * Get seqno
     *
     * @return integer
     */
    public function getSeqno()
    {
        return $this->seqno;
    }

    /**
     * Set clnSeqno
     *
     * @param \AppBundle\Entity\ContainerLocalizations $clnSeqno
     * @return EventStates
     */
    public function setClnSeqno(\AppBundle\Entity\ContainerLocalizations $clnSeqno = null)
    {
        $this->clnSeqno = $clnSeqno;

        return $this;
    }

    /**
     * Get clnSeqno
     *
     * @return \AppBundle\Entity\ContainerLocalizations
     */
    public function getClnSeqno()
    {
        return $this->clnSeqno;
    }

    /**
     * @return \AppBundle\Entity\Spec2Events
     */
    public function getSpec2Events()
    {
        return $this->spec2events;
    }

    /**
     * @param \AppBundle\Entity\Spec2Events $spec2events
     * @return EventStates
     */
    public function setSpec2Events($spec2events)
    {
        $this->spec2events = $spec2events;
        $spec2events->setEseSeqno($this);
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvent2Persons()
    {
        return $this->event2Persons;
    }

    /**
     * @return CgRefCodes
     */
    public function getEventDatetimeFlagRef()
    {
        return $this->eventDatetimeFlagRef;
    }

    /**
     * @param CgRefCodes $eventDatetimeFlagRef
     * @return EventStates
     */
    public function setEventDatetimeFlagRef($eventDatetimeFlagRef)
    {
        $this->eventDatetimeFlagRef = $eventDatetimeFlagRef;
        return $this;
    }

    public function getEventDatetimeFlag(){
        return $this->getEventDatetimeFlagRef()->getRvLowValue();
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $event2Persons
     * @return EventStates
     */
    public function setEvent2Persons($event2Persons)
    {
        $this->event2Persons = $event2Persons;
        return $this;
    }

    /**
     * @param \AppBundle\Entity\Event2Persons $event2Person
     */
    public function addEvent2Persons($event2Person)
    {
        if (!$this->getEvent2Persons()->contains($event2Person)) {
           // $event2Person->addEvent($this); //TODO: check removal
            $this->getEvent2Persons()->add($event2Person);
        }
    }

    public function removeEvent2Persons($event2Person)
    {
        if ($this->getEvent2Persons()->contains($event2Person)) {
            $this->getEvent2Persons()->removeElement($event2Person);
        }
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $event2persons
     * @return EventStates
     */
    public function setObservers(\Doctrine\Common\Collections\Collection $event2persons)
    {
        // foreach ($event2persons as $e2p) {
        //     $e2p->setE2pType(OBSERVED);
        // }
        $this->event2Persons = new \Doctrine\Common\Collections\ArrayCollection(array_merge($this->getEvent2Persons()->toArray(), $event2persons->toArray()));
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getObservers()
    {
        return $this->getEvent2Persons()->filter(
            function ($entry) {
                return $entry->getE2pType() == EventStates::OBSERVED;
            }
        );
    }

    /**
     * @param \AppBundle\Entity\Event2Persons $event2Person
     */
    public function addObservers($event2Person)
    {
        //$event2Person->setE2pType(EventStates::OBSERVED);
        if (!$this->getEvent2Persons()->contains($event2Person)) {
            $event2Person->setEseSeqno($this);
            $this->getEvent2Persons()->add($event2Person);
        }
    }

    /**
     * @param \AppBundle\Entity\Event2Persons $event2Person
     */
    public function removeObservers($event2Person)
    {
        if ($this->getEvent2Persons()->contains($event2Person)) {
            $this->getEvent2Persons()->removeElement($event2Person);
        }
    }

    /*---------------*/

    /**
     * @param \Doctrine\Common\Collections\Collection $event2persons
     * @return EventStates
     */
    public function setCollectors(\Doctrine\Common\Collections\Collection $event2persons)
    {
        // foreach ($event2persons as $e2p) {
        //     $e2p->setE2pType(EventStates::COLLECTED);
        // }
        $this->event2Persons = new \Doctrine\Common\Collections\ArrayCollection(array_merge($this->getEvent2Persons()->toArray(), $event2persons->toArray()));
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCollectors()
    {
        return $this->getEvent2Persons()->filter(
            function ($entry) {
                return $entry->getE2pType() == EventStates::COLLECTED;
            }
        );
    }

    /**
     * @param \AppBundle\Entity\Event2Persons $event2Person
     */
    public function addCollectors($event2Person)
    {
        //$event2Person->setE2pType(EventStates::COLLECTED);
        if (!$this->getEvent2Persons()->contains($event2Person)) {
            $event2Person->setEseSeqno($this);
            $this->getEvent2Persons()->add($event2Person);
        }
    }

    /**
     * @param \AppBundle\Entity\Event2Persons $event2Person
     */
    public function removeCollectors($event2Person)
    {
        if ($this->getEvent2Persons()->contains($event2Person)) {
            $this->getEvent2Persons()->removeElement($event2Person);
        }
    }

    /*---------------*/

    /**
     * @param \Doctrine\Common\Collections\Collection $event2persons
     * @return EventStates
     */
    public function setExaminers(\Doctrine\Common\Collections\Collection $event2persons)
    {
        $this->event2Persons = new \Doctrine\Common\Collections\ArrayCollection(array_merge($this->getEvent2Persons()->toArray(), $event2persons->toArray()));
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExaminers()
    {
        return $this->getEvent2Persons()->filter(
            function ($entry) {
                return $entry->getE2pType() == EventStates::EXAMINED;
            }
        );
    }

    /**
     * @param \AppBundle\Entity\Event2Persons $event2Person
     */
    public function addExaminers($event2Person)
    {
        //$event2Person->setE2pType(EventStates::COLLECTED);
        if (!$this->getEvent2Persons()->contains($event2Person)) {
            $event2Person->setEseSeqno($this);
            $this->getEvent2Persons()->add($event2Person);
        }
    }

    /**
     * @param \AppBundle\Entity\Event2Persons $event2Person
     */
    public function removeExaminers($event2Person)
    {
        if ($this->getEvent2Persons()->contains($event2Person)) {
            $this->getEvent2Persons()->removeElement($event2Person);
        }
    }

    /*---------------*/
    /**
     * @param \Doctrine\Common\Collections\Collection $event2persons
     * @return EventStates
     */
    public function setInformers(\Doctrine\Common\Collections\Collection $event2persons)
    {
        $this->event2Persons = new \Doctrine\Common\Collections\ArrayCollection(array_merge($this->getEvent2Persons()->toArray(), $event2persons->toArray()));
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInformers()
    {
        return $this->getEvent2Persons()->filter(
            function ($entry) {
                return $entry->getE2pType() == EventStates::INFORMED;
            }
        );
    }

    /**
     * @param \AppBundle\Entity\Event2Persons $event2Person
     */
    public function addInformers($event2Person)
    {
        //$event2Person->setE2pType(EventStates::COLLECTED);
        if (!$this->getEvent2Persons()->contains($event2Person)) {
            $event2Person->setEseSeqno($this);
            $this->getEvent2Persons()->add($event2Person);
        }
    }

    /**
     * @param \AppBundle\Entity\Event2Persons $event2Person
     */
    public function removeInformers($event2Person)
    {
        if ($this->getEvent2Persons()->contains($event2Person)) {
            $this->getEvent2Persons()->removeElement($event2Person);
        }
    }

    /*---------------*/
    /**
     * @return array
     */
    public function getObserverPersons()
    {
        return $this->getObservers()->map(function ($entry) {
            return $entry->getPsnSeqno();
        })->toArray();
    }

    /**
     * @return array
     */
    public function getCollectorPersons()
    {
        return $this->getCollectors()->map(function ($entry) {
            return $entry->getPsnSeqno();
        })->toArray();
    }

    /**
     * @return array
     */
    public function getInformerPersons()
    {
        return $this->getInformers()->map(function ($entry) {
            return $entry->getPsnSeqno();
        })->toArray();
    }

    /**
     * @return array
     */
    public function getExaminerPersons()
    {
        return $this->getExaminers()->map(function ($entry) {
            return $entry->getPsnSeqno();
        })->toArray();
    }

    /*---------------*/
    /**
     * @return string
     */
    public function getObserversAsString()
    {
        $result=array();
        foreach($this->getObserverPersons() as $i){
            array_push($result,$i->getFullyQualifiedName());
        }
        return implode(', ',$result);
    }

    /**
     * @return string
     */
    public function getCollectorsAsString()
    {
        $result=array();
        foreach($this->getCollectorPersons() as $i){
            array_push($result,$i->getFullyQualifiedName());
        }
        return implode(', ',$result);
    }

    /**
     * @return string
     */
    public function getInformersAsString()
    {
        $result=array();
        foreach($this->getInformerPersons() as $i){
            array_push($result,$i->getFullyQualifiedName());
        }
        return implode(', ',$result);
    }

    /**
     * @return string
     */
    public function getExaminersAsString()
    {
        $result=array();
        foreach($this->getExaminerPersons() as $i){
            array_push($result,$i->getFullyQualifiedName());
        }
        return implode(', ',$result);
    }
    /*----------------*/

    /**
     * @return \AppBundle\Entity\Observations
     */
    public function getObservation()
    {
        return $this->observation;
    }

    /**
     * @param \AppBundle\Entity\Observations $observation
     * @return EventStates
     */
    public function setObservation($observation)
    {
        $this->observation = $observation;
        return $this;
    }

    /**
     * @return Necropsies
     */
    public function getNecropsy()
    {
        return $this->necropsy;
    }

    /**
     * @param Necropsies $necropsy
     * @return EventStates
     */
    public function setNecropsy($necropsy)
    {
        $this->necropsy = $necropsy;
        return $this;
    }

    public function hasNecropsyAttached(){
        return $this->getNecropsy() !== null;
    }

    public function hasObservationAttached(){
        return $this->getObservation() !== null;
    }

    public function isEitherNecropsyOrObservationLegal(){
        return ($this->hasNecropsyAttached() && !$this->hasObservationAttached()) || (!$this->hasNecropsyAttached() && $this->hasObservationAttached());
    }

    public function getNecropsyCode(){
        if($this->hasNecropsyAttached()){
            return $this->getNecropsy()->getRef();
        }
    }
    /**
     * @return Specimens
     */
    public function getSpecimen(){
        return $this->getSpec2Events()->getScnSeqno();
    }

//
//    /**
//     * @param string $time
//     * @return EventStates
//     */
//    public function setTime($time)
//    {
//        $match = $this->isTime($time);
//        if ($this->getEventDatetime()) {
//            if ($match) {
//                $this->getEventDatetime()->setTime($match[1], $match[2]);
//            } else {
//            }
//        }
//        return $this;
//    }
//
//    /**
//     * @return string
//     */
//    public function getTime()
//    {
//        return $this->getEventDatetime()->format("H:i");
//    }
//
//    /**
//     * @param string $time
//     * @return boolean
//     */
//    public static function isTime($time)
//    {
//        $match = array();
//        if (preg_match('/^([0-9]{1,2}):([0-9]{2})/', $time, $match) === 1) {
//            return $match;
//        } else return null;
//    }
//
//    /**
//     * @param string $date
//     * @return EventStates
//     */
//    public function setDate($date)
//    {
//
//        $dt = \DateTime::createFromFormat('d/m/Y', $date, new \DateTimeZone('Europe/Paris'));
//        if($dt !== null){
//            $this->setEventDatetime($dt);
//        }
//        return $this;
//    }
//
//    /**
//     * @return string
//     */
//    public function getDate()
//    {
//        return $this->getEventDatetime()->format('d/m/Y');
//    }

}
