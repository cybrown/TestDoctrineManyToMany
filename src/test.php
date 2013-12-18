<?php
include __DIR__ . '/../vendor/autoload.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Entities\Annonce;
use Entities\Site;
use Entities\User;

$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . '/Entities'), $isDevMode);

$conn = array(
    'driver' => 'pdo_sqlite',
    'path' => __DIR__ . '/db.sqlite',
);

$em = EntityManager::create($conn, $config);

$tool = new SchemaTool($em);
$classes = array(
    $em->getClassMetadata('Entities\Annonce'),
    $em->getClassMetadata('Entities\Site'),
    $em->getClassMetadata('Entities\User')
);
$tool->dropSchema($classes);
$tool->updateSchema($classes);

$valenciennes = new Site();
$valenciennes->setName('Valenciennes');
$lille = new Site();
$lille->setName('Lille');
$paris = new Site();
$paris->setName('Paris');

$cy = new User();
$cy->setName('Cy');
$toto = new User();
$toto->setName('Toto');

$annonce1 = new Annonce();
$annonce1->setTitle('Annonce Valenciennes et Lille de Cy');
$annonce2 = new Annonce();
$annonce2->setTitle('Annonce Valenciennes, Lille et Paris de Toto');
$annonce3 = new Annonce();
$annonce3->setTitle('Annonce Lille et Paris de Cy');
$annonce4 = new Annonce();
$annonce4->setTitle('Annonce Paris de Toto');

$annonce1->addSite($valenciennes);
$annonce1->addSite($lille);
$annonce2->addSite($valenciennes);
$annonce2->addSite($lille);
$annonce2->addSite($paris);
$annonce3->addSite($lille);
$annonce3->addSite($paris);
$annonce4->addSite($paris);

$annonce1->setOwner($cy);
$annonce2->setOwner($toto);
$annonce3->setOwner($cy);
$annonce4->setOwner($toto);

$em->persist($annonce1);
$em->persist($annonce2);
$em->persist($annonce3);
$em->persist($annonce4);
$em->persist($valenciennes);
$em->persist($lille);
$em->persist($paris);
$em->persist($cy);
$em->persist($toto);
$em->flush();

echo "Annonces ayant comme sites Valenciennes ou Lille au moins et appartenant a Cy:\n";
$query = $em->createQuery("SELECT a FROM Entities\\Annonce a JOIN a.sites s JOIN a.owner o WHERE s.name IN (:sites) AND o.name = :owner");
$query->setParameter('sites', array('Valenciennes', 'Lille'));
$query->setParameter('owner', 'Cy');
$annonces = $query->getResult();
foreach ($annonces as $annonce) {
    echo '- ';
    echo $annonce->getTitle();
    echo "\n";
}

echo "\n";
echo "Annonces ayant comme sites tout sauf EXCLUSIVEMENT Paris et appartenant a Toto:\n";
$query = $em->createQuery("SELECT a FROM Entities\\Annonce a JOIN a.sites s JOIN a.owner o WHERE s.name NOT IN (:sites) AND o.name = :owner");
$query->setParameter('sites', array('Paris'));
$query->setParameter('owner', 'Toto');
$annonces = $query->getResult();
foreach ($annonces as $annonce) {
    echo '- ';
    echo $annonce->getTitle();
    echo "\n";
}

echo "\n";
echo "Annonces ayant comme sites tout sauf Paris ou Lille uniquement (donc au moins Valenciennes...) et appartenant a n'importe qui:\n";
$query = $em->createQuery("SELECT a FROM Entities\\Annonce a JOIN a.sites s WHERE s.name NOT IN (:sites)");
$query->setParameter('sites', array('Paris', 'Lille'));
$annonces = $query->getResult();
foreach ($annonces as $annonce) {
    echo '- ';
    echo $annonce->getTitle();
    echo "\n";
}
