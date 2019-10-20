<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 19/10/19
 * Time: 10:43
 */

namespace App\Proxy;


use App\Model\Proxy;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;

class Blacklisted
{

    public static function is(Proxy $proxy ) {


        $goutteClient = new Client();
        $guzzleClient = new GuzzleClient(array(
            'timeout' => 600,
        ));
        $goutteClient->setClient($guzzleClient);

        $url = 'http://localhost:8000/search/la+vie/page/0/host/' . $proxy->getHost().'/port/'. $proxy->getPort();

        $crawler = $goutteClient->request('GET', $url);

        $data = $crawler->filter('body > div > div')->children();

        if(preg_match(
            '/This page appears when Google automatically detects requests/',
            $data->getNode(6)->textContent
            )
        ) {
            return true;
        } else {
            return false;
        }
    }
}
