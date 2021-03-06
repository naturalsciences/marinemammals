<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LesionValues
 *
 * @ORM\Table(name="LESION_VALUES", indexes={@ORM\Index(name="lve_pmd_fk_i", columns={"PMD_SEQNO"}), @ORM\Index(name="idx_oln_seqno", columns={"OLN_SEQNO"})})
 * @ORM\Entity
 */
class LesionValues implements EntityValues
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
     * @var string
     *
     * @ORM\Column(name="VALUE", type="string", length=50, nullable=false)
     */
    private $value;

    /**
     * @var integer
     *
     * @ORM\Column(name="SEQNO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="LESION_VALUES_SEQ", allocationSize=1, initialValue=1)
     */
    private $seqno;

    /**
     * @var \AppBundle\Entity\ParameterMethods
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ParameterMethods")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PMD_SEQNO", referencedColumnName="SEQNO", nullable=false)
     * })
     */
    private $pmdSeqno;

    /**
     * @var \AppBundle\Entity\OrganLesions
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\OrganLesions", inversedBy="values")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="OLN_SEQNO", referencedColumnName="SEQNO", nullable=false)
     * })
     */
    private $valueAssignable;

    /**
     * @var \AppBundle\Entity\CgRefCodes
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CgRefCodes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="VALUE_FLAG_REF", referencedColumnName="SEQNO")
     * })
     */
    private $valueFlagRef;

    /**
     * @var boolean
     *
     */
    private $hasFlag;

    /**
     * @var boolean
     *
     */
    private $valueRequired;

    /**
     * Set creDat
     *
     * @param \DateTime $creDat
     * @return LesionValues
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
     * @return LesionValues
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
     * @return LesionValues
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
     * @return LesionValues
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
     * Set value
     *
     * @param string $value
     * @return LesionValues
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
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
     * Set pmdSeqno
     *
     * @param \AppBundle\Entity\ParameterMethods $pmdSeqno
     * @return LesionValues
     */
    public function setPmdSeqno(\AppBundle\Entity\ParameterMethods $pmdSeqno = null)
    {
        $this->pmdSeqno = $pmdSeqno;
    
        return $this;
    }

    /**
     * Get pmdSeqno
     *
     * @return \AppBundle\Entity\ParameterMethods 
     */
    public function getPmdSeqno()
    {
        return $this->pmdSeqno;
    }

    /**
     * Set valueAssignable
     *
     * @param \AppBundle\Entity\ValueAssignable $valueAssignable
     * @return LesionValues
     * @throws \Exception
     */
    public function setValueAssignable(ValueAssignable $valueAssignable)
    {
        if (get_class($valueAssignable) !== 'AppBundle\Entity\OrganLesions') {
            throw new \Exception('type of $valueAssignable must be of type OrganLesions');
        } else {
            $this->valueAssignable = $valueAssignable;
            $valueAssignable->addValue($this);
            return $this;
        }
    }

    /**
     * Get valueAssignable
     *
     * @return \AppBundle\Entity\OrganLesions
     */
    public function getValueAssignable()
    {
        return $this->valueAssignable;
    }

    /**
     * @return CgRefCodes
     */
    public function getValueFlagRef()
    {
        return $this->valueFlagRef;
    }

    /**
     * @param CgRefCodes $valueFlagRef
     * @return LesionValues
     */
    public function setValueFlagRef($valueFlagRef)
    {
        $this->valueFlagRef = $valueFlagRef;
        return $this;
    }

    public function getValueFlag(){
        return $this->getValueFlagRef()->getRvLowValue();
    }
    
    /**
     * Get the name of the used parameter method's name
     *
     * @return string
     */
    public function getPmdName(){
        return $this->getPmdSeqno()->getName();
    }

    /**
     * @return boolean
     */
    public function getHasFlag()
    {
        return $this->hasFlag;
    }

    /**
     * @param boolean $hasFlag
     * @return LesionValues
     */
    public function setHasFlag($hasFlag)
    {
        $this->hasFlag = $hasFlag;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getValueRequired()
    {
        return $this->valueRequired;
    }

    /**
     * @param boolean $valueRequired
     * @return LesionValues
     */
    public function setValueRequired($valueRequired)
    {
        $this->valueRequired = $valueRequired;
        return $this;
    }

    /**
     * Get whether the value is legal, i.e. has a flag. Note that flagged but empty values are legal!
     *
     * @return boolean
     */
    public function isValueFlagLegal()
    {
        if ($this->getHasFlag() && $this->getValueFlagRef() === NULL && $this->getValue() !== NULL) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get whether the value itself is legal.
     *
     * @return boolean
     */
    public function isValueUnwantedLegal()
    {
        return false;
    }

    /**
     * Get whether the value itself must be completed.
     *
     * @return boolean
     */
    public function isValueLegal(){
        return $this->getValueRequired() && !$this->isValueUnwantedLegal();
    }
}
