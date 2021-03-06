<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Organs
 *
 * @ORM\Table(name="ORGANS", indexes={@ORM\Index(name="ogn_ogn_fk_i", columns={"OGN_CODE"})})
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\OrgansRepository")
 */
class Organs
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
     * @ORM\Column(name="NAME", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="CODE", type="string", length=10)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="ORGANS_CODE_seq", allocationSize=1, initialValue=1)
     */
    private $code;

    /**
     * @var \AppBundle\Entity\Organs
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Organs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="OGN_CODE", referencedColumnName="CODE")
     * })
     */
    private $ognCode;



    /**
     * Set creDat
     *
     * @param \DateTime $creDat
     * @return Organs
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
     * @return Organs
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
     * @return Organs
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
     * @return Organs
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
     * Set name
     *
     * @param string $name
     * @return Organs
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set ognCode
     *
     * @param \AppBundle\Entity\Organs $ognCode
     * @return Organs
     */
    public function setOgnCode(\AppBundle\Entity\Organs $ognCode = null)
    {
        $this->ognCode = $ognCode;
    
        return $this;
    }

    /**
     * Get ognCode
     *
     * @return \AppBundle\Entity\Organs 
     */
    public function getOgnCode()
    {
        return $this->ognCode;
    }
}
