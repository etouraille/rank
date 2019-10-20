<?php

namespace App\Command;

use App\Model\Proxy;
use App\Proxy\Load;
use App\WriteBlog\Content\Provider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Scrap\GatherProxy;
use App\Scrap\google;

class LoadGoogle extends Command
{
    // the name of sthe command (the part after "bin/console")
    protected static $defaultName = 'google';

    public function __construct( EntityManagerInterface $em, ContainerInterface $container ) {
        $this->em = $em;
        $this->commandHandler = $container->get('command_bus');

        parent::__construct();

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //$load = GatherProxy::load();
        /*
        $proxy = new Proxy('5.196.132.126',3128);
        var_dump(\App\Proxy\Blacklisted::is($proxy));

        $proxies = new Load();
        $proxies->load();
        */
        $textProvider = new Provider($this->em, $this->commandHandler , 3 );
        var_dump( $textProvider->getText(10 ));

    }
}

