<?php

namespace App\Scrap;

use App\Model\Proxy;
use JonnyW\PhantomJs\Client;
use Symfony\Component\DomCrawler\Crawler;

class GatherProxy
{
    public static function load() {


        $client = Client::getInstance();
        $client->setPhantomJs('/usr/local/bin/phantomjs');

        $request = $client->getMessageFactory()->createRequest('GET', 'http://www.gatherproxy.com/fr/proxylist/country/?c=France' );
        $response = $client->getMessageFactory()->createResponse();
        $client->send($request, $response);
        $data = $response->getContent();

        $crawler = new Crawler($data);

        $index = 0;
        $row = -1;
        $proxies = [];

        $crawler->filter('#tblproxy > tbody > tr > td')->each(function( $node ) use ( &$index, &$row , &$proxies ) {
            if ( 0 === ( $index % 8 ) ) {
                $row ++;
            }
            if ( 2 === $index - ( $row * 8 ) ) {
                if(!isset($proxies[$row])) $proxies[$row] = new Proxy( null );
                $proxies[$row]->setHttp( $node->text() );
            }
            if ( 3 === ( $index - ( $row * 8 ) ) ) {
                $proxies[$row]->setPort( $node->text() );
            }
            $index ++;
        });

        return $proxies ;
    }
}
