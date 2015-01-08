<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Taxa
 *
 * @ORM\Table(name="TAXA")
 * @ORM\Entity
 */
class Taxa
{
    /**
     * @var string
     *
     * @ORM\Column(name="CANONICAL_NAME", type="string", length=50, nullable=true)
     */
    private $canonicalName;

    /**
     * @var integer
     *
     * @ORM\Column(name="IDOD_ID", type="integer", nullable=false)
     */
    private $idodId;

    /**
     * @var string
     *
     * @ORM\Column(name="SCIENTIFIC_NAME_AUTHORSHIP", type="string", length=50, nullable=true)
     */
    private $scientificNameAuthorship;

    /**
     * @var string
     *
     * @ORM\Column(name="TAXONRANK", type="string", length=50, nullable=true)
     */
    private $taxonrank;

    /**
     * @var string
     *
     * @ORM\Column(name="VERNACULAR_NAME_EN", type="string", length=50, nullable=true)
     */
    private $vernacularNameEn;

    /**
     * @var integer
     *
     * @ORM\Column(name="SEQNO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="TAXA_SEQNO_seq", allocationSize=1, initialValue=1)
     */
    private $seqno;


}