<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Medias
 *
 * @ORM\Table(name="MEDIAS", indexes={@ORM\Index(name="mda_oln_fk_i", columns={"OLN_NCY_ESE_SEQNO", "OLN_LTE_OGN_CODE", "OLN_LTE_SEQNO"}), @ORM\Index(name="mda_psn_fk_i", columns={"PSN_SEQNO"}), @ORM\Index(name="mda_ese_fk_i", columns={"ESE_SEQNO"})})
 * @ORM\Entity
 */
class Medias
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="CONFIDENTIALITY", type="boolean", nullable=true)
     */
    private $confidentiality;

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
     * @ORM\Column(name="DESCRIPTION", type="string", length=4000, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="DISPLAY", type="string", length=1, nullable=true)
     */
    private $display;

    /**
     * @var string
     *
     * @ORM\Column(name="LOCATION", type="string", length=200, nullable=false)
     */
    private $location;

    /**
     * @var string
     *
     * @ORM\Column(name="MDA_TYPE", type="string", length=3, nullable=false)
     */
    private $mdaType;

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
     * @ORM\SequenceGenerator(sequenceName="MEDIAS_SEQNO_seq", allocationSize=1, initialValue=1)
     */
    private $seqno;

    /**
     * @var \AppBundle\Entity\Persons
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Persons")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PSN_SEQNO", referencedColumnName="SEQNO")
     * })
     */
    private $psnSeqno;

    /**
     * @var \AppBundle\Entity\OrganLesions
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\OrganLesions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="OLN_NCY_ESE_SEQNO", referencedColumnName="NCY_ESE_SEQNO"),
     *   @ORM\JoinColumn(name="OLN_LTE_OGN_CODE", referencedColumnName="LTE_OGN_CODE"),
     *   @ORM\JoinColumn(name="OLN_LTE_SEQNO", referencedColumnName="LTE_SEQNO")
     * })
     */
    private $olnNcyEseSeqno;

    /**
     * @var \AppBundle\Entity\EventStates
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\EventStates")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ESE_SEQNO", referencedColumnName="SEQNO")
     * })
     */
    private $eseSeqno;


}