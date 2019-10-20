<?php

namespace App\Scrap;

//use Goutte\Client;
//use GuzzleHttp\Client as GuzzleClient;
use App\Entity\Sentence;
use JonnyW\PhantomJs\Client;
use Symfony\Component\DomCrawler\Crawler;
use Doctrine\ORM\EntityManager;

/* Script pour charger des phrase de text brut dans les titre du Journal Le Monde*/

class load {


    public function __construct(EntityManager $em) {

        //$client = new Client();

        $client = Client::getInstance();
        //$client->getEngine()->debug(true);
        $client->setPhantomJs('/usr/local/bin/phantomjs');



        $request = $client->getMessageFactory()->createRequest('GET', 'https://www.lemonde.fr' );

        $response = $client->getMessageFactory()->createResponse();

        // Send the request
        $client->send($request, $response);

        $data = $response->getContent();


        $crawler = new Crawler($data);

        /*
        $guzzleClient = new GuzzleClient(array(
            'timeout' => 60,
        ));
        $client->setClient($guzzleClient);


        $crawler = $client->request('GET', 'https://www.lemonde.fr/archives-du-monde/03-01-97');

        */

        $link = $crawler->filterXPath('//a')->each(function($node)  use( $em ) {
            $href = $node->attr('href');
            if(preg_match("/https:\/\/www\.lemonde\.fr/", $href)) {


              $client = Client::getInstance();
              $client->setPhantomJs('/usr/local/bin/phantomjs');


              $request = $client->getMessageFactory()->createRequest('GET', $href );

              $response = $client->getMessageFactory()->createResponse();

              // Send the request
              $client->send($request, $response);

              $data = $response->getContent();

              $crawler = new Crawler($data);

              $text = '';
              $n = 0;

              $link = $crawler->filterXPath('//p')->each(function($node ) use( $em, $href, $n) {
                $text = $node->text();

                preg_match("/(([A-Z])(.+)(\?|\.|\.\.\.|\!))/U", $text, $match);

                if(isset($match[1])){

                  $sentence = $match[1];


                  if( preg_match("/Temps de Lecture/", $sentence )) {

                  } elseif( preg_match("/abonner, c/", $sentence )) {

                  } elseif(preg_match("/La suite est réservée/", $sentence)) {

                  } elseif(preg_match("/Accédez à tous les/", $sentence )) {

                  } elseif( preg_match('/Soutenez le journalisme/', $sentence )) {

                  } elseif ( preg_match("/Consultez le journal en version numérique et ses/", $sentence )) {

                  } elseif( preg_match("/Le Monde utilise des cookies/", $sentence )) {

                  } elseif(preg_match("/https:\/\//", $sentence )) {
                  }else {
                    // ajouter une phrase si elle n'existe pas déjà.

                      $foundSentence = $em->getRepository('App\\Entity\\Sentence')->findOneByValue($sentence);
                      if(!isset($foundSentence) ) {
                          $entity = new Sentence();
                          $entity->setValue($sentence);
                          $entity->setArticleRank($n);
                          $entity->setUrl($href);
                          $entity->setLength($entity->getLength());
                          $em->persist($entity);
                          $em->flush();

                      }

                      $n ++;
                      print $sentence."\n";

                  }
                }





              });
            }
        });

        //$crawler = $client->click($link);






    }
}
