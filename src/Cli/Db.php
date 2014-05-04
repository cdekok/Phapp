<?php

namespace Phapp\Cli;

class Db extends Symfony\Component\Console\Command\Command {

    protected function configure() {
        $this
                ->setName('phapp:db')
                ->setDescription('Create database')
                ->addArgument(
                        'db', InputArgument::OPTIONAL, 'Create database tables'
        );
    }

    protected function execute(\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output) {
        $name = $input->getArgument('name');
        if ($name) {
            $text = 'Hello ' . $name;
        } else {
            $text = 'Hello';
        }

        if ($input->getOption('yell')) {
            $text = strtoupper($text);
        }

        $output->writeln($text);
    }

}
