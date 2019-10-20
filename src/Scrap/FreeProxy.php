<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 18/10/19
 * Time: 15:52
 */

namespace App\Scrap;

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;

class FreeProxy
{

    public function __construct() {

    }

    public static function load() {

        $client = new Client();
        $guzzleClient = new GuzzleClient(array(
            'timeout' => 60,
        ));
        $client->setClient($guzzleClient);
        $crawler = $client->request('GET', 'http://free-proxy.cz/fr/proxylist/country/FR/http/ping/all');
        $crawler->filterXPath('//*[@id="proxy_list"]/tbody/tr')->each(function( $node) {
           var_dump( $node->text());
        });
    }
}
