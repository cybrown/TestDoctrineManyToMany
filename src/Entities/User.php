<?php

namespace Entities;

/**
 * @Entity
 **/
class User {
    /** @Id @Column(type="integer") @GeneratedValue **/
    private $id;
    /** @Column(type="string") **/
    private $name;
    /**
     * @OneToMany(targetEntity="Annonce", mappedBy="owner")
     **/
    private $annonces = array();

    public function getAnnonces() {
        return $this->annonces;
    }

    public function addAnnonce(Annonce $annonce) {
        $this->annonces[] = $annonce;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }
}
