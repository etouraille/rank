<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Scrap\load;
class LoadCommand extends Command
{
    // the name of sthe command (the part after "bin/console")
    protected static $defaultName = 'load';

    public function __construct(\Doctrine\ORM\EntityManagerInterface $em ) {
        $this->em = $em;
        parent::__construct();
    }

    protected function configure()
    {
        // ...
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $load = new load($this->em);
    }
}
