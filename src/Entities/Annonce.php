<?php

namespace Entities;

/**
 * @Entity
 **/
class Annonce {
    /** @Id @Column(type="integer") @GeneratedValue **/
    private $id;
    /** @Column(type="string") **/
    private $title;
    /**
     * @ManyToMany(targetEntity="Site", inversedBy="annonces")
     **/
    private $sites = array();
    /**
     * @ManyToOne(targetEntity="User", inversedBy="annonces")
     **/
    private $owner;

    public function setOwner(User $owner) {
        $owner->addAnnonce($this);
        $this->owner = $owner;
    }

    public function getOwner() {
        return $this->owner;
    }

    public function getSites() {
        return $this->sites;
    }

    public function addSite(Site $site) {
        $this->sites[] = $site;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getTitle() {
        return $this->title;
    }
}
