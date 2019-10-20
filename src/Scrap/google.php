<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 18/10/19
 * Time: 15:38
 */

namespace App\Scrap;

use App\Model\Proxy;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;

class google
{

    public static function load(Proxy $proxy ) {


        $config = [
            'proxy' => [
                'http' => $proxy->getHttp().':'.$proxy->getPort(),
            ],
            'timeout' => 60,
        ];


        $client = new Client();
        $guzzleClient = new GuzzleClient($config);
        $client->setClient($guzzleClient);
        $crawler = $client->request('GET', 'https://www.google.com/search?q=la+vie');

        var_dump($crawler);

        $crawler->filterXPath('//*[@id="rso"]/div/div/div[1]/div/div/div[1]/a')->each(function( $node ) {
           var_dump($node->attribute('href'));
        });



    }
}
