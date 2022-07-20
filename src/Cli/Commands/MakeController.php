<?php

namespace Lune\Cli\Commands;

use Lune\App;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeController extends Command {
    protected static $defaultName = "make:controller";

    protected static $defaultDescription = "Create a new controller";

    protected function configure() {
        $this->addArgument("name", InputArgument::REQUIRED, "Controller name");
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $name = $input->getArgument("name");
        $template = file_get_contents(resourcesDirectory() . "/templates/controller.php");
        $template = str_replace("ControllerName", $name, $template);
        file_put_contents(App::$root . "/app/Controllers/$name.php", $template);
        $output->writeln("<info>Controller created => $name.php</info>");

        return Command::SUCCESS;
    }
}
