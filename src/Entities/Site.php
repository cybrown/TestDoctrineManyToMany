<?php

namespace Entities;

/**
 * @Entity
 **/
class Site {
    /** @Id @Column(type="integer") @GeneratedValue **/
    private $id;
    /** @Column(type="string") **/
    private $name;
    /**
     * @ManyToMany(targetEntity="Annonce", mappedBy="sites")
     **/
    private $annonces = array();

    public function getAnnonces() {
        return $this->annonces;
    }

    public function addAnnonce(Annonce $annonce) {
        $annonce->addSite($this);
        $this->annonces[] = $annonce;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }
}
