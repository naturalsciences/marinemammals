<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Spec2Events SPEC2EVENTS
 *
 * @ORM\Table(name="SPEC2EVENTS", indexes={@ORM\Index(name="S2E_PK", columns={"SCN_SEQNO","ESE_SEQNO"}), @ORM\Index(name="S2E_ESE_FK_I", columns={"ESE_SEQNO"})})
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\Spec2EventsRepository")
 */
class Spec2Events implements ValueAssignable
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
     * @var \AppBundle\Entity\Specimens
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Specimens", inversedBy="spec2events")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SCN_SEQNO", referencedColumnName="SEQNO", nullable=false)
     * })
     */
    private $scnSeqno;

    /**
     * @var \AppBundle\Entity\EventStates
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\EventStates", inversedBy="spec2events")
     * @ORM\OrderBy({"eventDateTime" = "DESC"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ESE_SEQNO", referencedColumnName="SEQNO", nullable=false)
     * })
     */
    private $eseSeqno;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SpecimenValues", mappedBy="valueAssignable")
     */
    private $values;

    private $bycatchProbabilityValues;

    private $causeOfDeathValues;

    private $pathologyValues;

    private $circumstantialValues;

    private $measurementValues;

    /**
     * @var boolean
     */
    private $usesExistingSpecimen;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->values = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set creDat
     *
     * @param \DateTime $creDat
     * @return Event2Persons
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
     * @return Event2Persons
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
     * Set modDat
     *
     * @param \DateTime $modDat
     * @return Event2Persons
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
     * @return Event2Persons
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
     * Set scnSeqno
     *
     * @param \AppBundle\Entity\Specimens $scnSeqno
     * @return Spec2Events
     */
    public function setScnSeqno(\AppBundle\Entity\Specimens $scnSeqno)
    {
        $this->scnSeqno = $scnSeqno;
        $scnSeqno->addToSpec2Events($this);
        return $this;
    }

    /**
     * Get scnSeqno
     *
     * @return \AppBundle\Entity\Specimens
     */
    public function getScnSeqno()
    {
        return $this->scnSeqno;
    }

    /**
     * Set eseSeqno
     *
     * @param \AppBundle\Entity\EventStates $eseSeqno
     * @return Event2Persons
     */
    public function setEseSeqno(\AppBundle\Entity\EventStates $eseSeqno)
    {
        $this->eseSeqno = $eseSeqno;
        return $this;
    }

    /**
     * Get eseSeqno
     *
     * @return \AppBundle\Entity\EventStates
     */
    public function getEseSeqno()
    {
        return $this->eseSeqno;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $values
     * @return Spec2Events
     */
    public function setValues(\Doctrine\Common\Collections\Collection $values)
    {
        $this->values = $values;
        return $this;
    }

    /**
     * @param \AppBundle\Entity\EntityValues $value
     */
    public function addValue(EntityValues $value)
    {
        $this->getValues()->add($value);
    }

    public function removeValue(EntityValues $ev)
    {
        $this->getValues()->removeElement($ev);
    }
//------------------------
    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCircumstantialValues()
    {
        $circumstantialPms=array();
        array_push($circumstantialPms,'Before intervention');
        array_push($circumstantialPms,'During intervention');
        array_push($circumstantialPms,'Collection');
        array_push($circumstantialPms,'Decomposition Code');

        return $this->getValues()->filter(
            function($entry) use ($circumstantialPms) {
                return in_array($entry->getPmdSeqno()->getName(), $circumstantialPms);
            }
        );
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $values
     * @return Spec2Events
     */
    public function setCircumstantialValues(\Doctrine\Common\Collections\Collection $values)
    {
        $this->circumstantialValues=$values;
        $this->values= new \Doctrine\Common\Collections\ArrayCollection(
            array_merge($this->getValues()->toArray(), $values->toArray())
        );
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMeasurementValues()
    {
        $measurementPms=array();
        array_push($measurementPms,'Body length');
        array_push($measurementPms,'Body weight');
        array_push($measurementPms,'Age');
        array_push($measurementPms,'Nutritional Status');

        return $this->getValues()->filter(
            function($entry) use ($measurementPms) {
                return in_array($entry->getPmdSeqno()->getName(), $measurementPms);
            }
        );
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $values
     * @return Spec2Events
     */
    public function setMeasurementValues(\Doctrine\Common\Collections\Collection $values)
    {
        $this->measurementValues=$values;
        $this->values= new \Doctrine\Common\Collections\ArrayCollection(
            array_merge($this->getValues()->toArray(), $values->toArray())
        );
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPathologyValues()
    {
        $pathologyPms=array();
        array_push($pathologyPms,'Fresh external lesions::Fresh bite marks');
        array_push($pathologyPms,'Fresh external lesions::Opened abdomen');
        array_push($pathologyPms,'Fresh external lesions::Stabbing wound');
        array_push($pathologyPms,'Fresh external lesions::Parallel cuts');
        array_push($pathologyPms,'Fresh external lesions::Broken bones');
        array_push($pathologyPms,'Fresh external lesions::Hypothema');
        array_push($pathologyPms,'Fresh external lesions::Fin amputation');
        array_push($pathologyPms,'Fresh external lesions::Encircling lesion');
        array_push($pathologyPms,'Fresh external lesions::Line/net impressions or cuts::Tail');
        array_push($pathologyPms,'Fresh external lesions::Line/net impressions or cuts::Pectoral fin');
        array_push($pathologyPms,'Fresh external lesions::Line/net impressions or cuts::Snout');
        array_push($pathologyPms,'Fresh external lesions::Line/net impressions or cuts::Mouth');
        array_push($pathologyPms,'Fresh external lesions::Scavenger traces::Picks');
        array_push($pathologyPms,'Fresh external lesions::Scavenger traces::Bites');
        array_push($pathologyPms,'Fresh external lesions::Other fresh external lesions');
        array_push($pathologyPms,'Healing/healed lesions::Bites');
        array_push($pathologyPms,'Healing/healed lesions::Pox-like lesions');
        array_push($pathologyPms,'Healing/healed lesions::Open warts');
        array_push($pathologyPms,'Healing/healed lesions::Cuts');
        array_push($pathologyPms,'Healing/healed lesions::Line/net impressions');
        array_push($pathologyPms,'Fishing activities::Static gear on beach nearby');
        array_push($pathologyPms,'Fishing activities::Static gear at sea nearby');
        array_push($pathologyPms,'Other characteristics::External parasites');
        array_push($pathologyPms,'Other characteristics::Froth from airways');
        array_push($pathologyPms,'Other characteristics::Fishy smell');
        array_push($pathologyPms,'Other characteristics::Prey remains in mouth');
        array_push($pathologyPms,'Other characteristics::Remains of nets, ropes, plastic, etc.');
        array_push($pathologyPms,'Other characteristics::Oil remains on skin');
        array_push($pathologyPms,'Nutritional condition');
        array_push($pathologyPms,'Stomach Content');
        array_push($pathologyPms,'Other remarks');

        return $this->getValues()->filter(
            function($entry) use ($pathologyPms) {
                return in_array($entry->getPmdSeqno()->getName(), $pathologyPms);
            }
        );
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $values
     * @return Spec2Events
     */
    public function setPathologyValues(\Doctrine\Common\Collections\Collection $values)
    {
        $this->pathologyValues=$values;
        $this->values= new \Doctrine\Common\Collections\ArrayCollection(
            array_merge($this->getValues()->toArray(), $values->toArray())
        );
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCauseOfDeathValues()
    {
        $codPms=array();
        array_push($codPms,'Cause of death::Natural');
        array_push($codPms,'Cause of death::Bycatch');
        array_push($codPms,'Cause of death::Ship strike');
        array_push($codPms,'Cause of death::Predation');
        array_push($codPms,'Cause of death::Other');
        array_push($codPms,'Cause of death::Unknown');
        return $this->getValues()->filter(
            function($entry) use ($codPms) {
                return in_array($entry->getPmdSeqno()->getName(), $codPms);
            }
        );
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $values
     * @return Spec2Events
     */
    public function setCauseOfDeathValues(\Doctrine\Common\Collections\Collection $values)
    {
        $this->causeOfDeathValues=$values;
        $this->values= new \Doctrine\Common\Collections\ArrayCollection(
            array_merge($this->getValues()->toArray(), $values->toArray())
        );
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBycatchProbabilityValues()
    {
        $bycPms=array();
        array_push($bycPms,'Bycatch activity::Professional gear');
        array_push($bycPms,'Bycatch activity::Recreational gear');
        array_push($bycPms,'Bycatch activity::Angling');
        array_push($bycPms,'Bycatch activity::Unknown fishing gear');
        return $this->getValues()->filter(
            function($entry) use ($bycPms) {
                return in_array($entry->getPmdSeqno()->getName(), $bycPms);
            }
        );
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $values
     * @return Spec2Events
     */
    public function setBycatchProbabilityValues(\Doctrine\Common\Collections\Collection $values)
    {
        $this->bycatchProbabilityValues=$values;
        $this->values= new \Doctrine\Common\Collections\ArrayCollection(
            array_merge($this->getValues()->toArray(), $values->toArray())
        );
        return $this;
    }

    /**
     * @return boolean
     */
    public function hasPertinentValues()
    {
        foreach($this->getValues() as $sv){
            if ($sv->getValue() !== null){
                return true;
            }
        }
        return false;
    }

    /**
     * @return boolean
     */
    public function getUsesExistingSpecimen()
    {
        return $this->usesExistingSpecimen;
    }

    /**
     * @param boolean $usesExistingSpecimen
     * @return Spec2Events
     */
    public function setUsesExistingSpecimen($usesExistingSpecimen)
    {
        $this->usesExistingSpecimen = $usesExistingSpecimen;
        return $this;
    }

    public function getValueByKey($key){
        foreach($this->getValues() as $ev){
            if($ev->getPmdName()===$key){
                return $ev;
            }
        }
    }


    public function hasValuesLegal(
        
    ){

}

}

