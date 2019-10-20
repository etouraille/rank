<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 19/10/19
 * Time: 10:41
 */

namespace App\Proxy;


use App\Entity\Proxy;
use App\Scrap\GatherProxy;
use Doctrine\ORM\EntityManagerInterface;

class Load
{


    private $providers = [];

    public function __construct(EntityManagerInterface $em ) {
        $this->providers[] = GatherProxy::load();

        $this->em = $em;
    }


    public function load() {
        foreach( $this->providers as $proxies ) {
            foreach( $proxies as $proxy ) {
                if ( Down::is($proxy) ) {
                    $proxy->setDown();
                } else {
                    $proxy->setUp();
                }
                if ( Blacklisted::is( $proxy ) ) {
                    $proxy->setBlacklisted();
                } else {
                    $proxy->setWhitlisted();
                }
                $entity = new Proxy();
                $entity->setHost( $proxy->getHost() );
                $entity->setPort( $proxy->setPort() );
                $entity->setDown( $proxy->isDown() );
                $entity->setBlacklisted( $proxy->isBlacklisted() );

                $found = $this->em->getRepository('App\\Entity\\Proxy')->findOneByHost($entity->getHost());
                if( isset( $found )) {
                    $found->setDown( $entity->isDown() );
                    $found->setBlacklisted( $entity->isBlacklisted() );
                    $this->em->merge( $found );
                    $this->em->flush();
                } else {
                    $this->em->persist( $entity );
                    $this->em->flush();
                }

            }
        }

    }
}
