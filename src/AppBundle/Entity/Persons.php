<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Persons
 *
 * @ORM\Table(name="PERSONS", uniqueConstraints={@ORM\UniqueConstraint(name="psn_uk", columns={"FIRST_NAME", "LAST_NAME"}), @ORM\UniqueConstraint(name="psn_email_uk", columns={"EMAIL"})}, indexes={@ORM\Index(name="psn_ite_fk_i", columns={"ITE_SEQNO"})})
 * @ORM\Entity
 */
class Persons
{
    /**
     * @var string
     *
     * @ORM\Column(name="ADDRESS", type="string", length=250, nullable=true)
     */
    private $address;

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
     * @ORM\Column(name="EMAIL", type="string", length=80, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="FIRST_NAME", type="string", length=15, nullable=true)
     */
    private $firstName;

    /**
     * @var integer
     *
     * @ORM\Column(name="IDOD_ID", type="integer", nullable=true)
     */
    private $idodId;

    /**
     * @var string
     *
     * @ORM\Column(name="LAST_NAME", type="string", length=25, nullable=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="LOGIN_NAME", type="string", length=25, nullable=true)
     */
    private $loginName;

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
     * @ORM\Column(name="PASSWORD", type="string", length=60, nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="PHONE_NUMBER", type="string", length=20, nullable=true)
     */
    private $phoneNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="PIC", type="string", length=100, nullable=true)
     */
    private $pic;

    /**
     * @var string
     *
     * @ORM\Column(name="SESSIONID", type="string", length=32, nullable=true)
     */
    private $sessionid;

    /**
     * @var string
     *
     * @ORM\Column(name="SEX", type="string", length=3, nullable=true)
     */
    private $sex;

    /**
     * @var string
     *
     * @ORM\Column(name="TITLE", type="string", length=15, nullable=true)
     */
    private $title;

    /**
     * @var integer
     *
     * @ORM\Column(name="SEQNO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="PERSONS_SEQNO_seq", allocationSize=1, initialValue=1)
     */
    private $seqno;

    /**
     * @var \AppBundle\Entity\Institutes
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Institutes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ITE_SEQNO", referencedColumnName="SEQNO")
     * })
     */
    private $iteSeqno;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\RequestLoans", mappedBy="psnSeqno")
     */
    private $rlnSeqno;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Groups", mappedBy="psnSeqno")
     */
    private $grpName;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rlnSeqno = new \Doctrine\Common\Collections\ArrayCollection();
        $this->grpName = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
