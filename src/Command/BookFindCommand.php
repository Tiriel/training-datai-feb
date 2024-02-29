<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:book:find',
    description: 'Add a short description for your command',
)]
class BookFindCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('lastname', InputArgument::OPTIONAL, 'Argument description')
            ->addArgument('firstname', InputArgument::OPTIONAL|InputArgument::IS_ARRAY, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_REQUIRED, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $firstname = $input->getArgument('firstname');

        if ($firstname) {
            $io->note(sprintf('You passed a firstname: %s', implode(', ', $firstname)));
        }

        $lastname = $input->getArgument('lastname');

        if (!$lastname) {
            $lastname = $io->ask("What is your lastname ?");
        }
        $io->note(sprintf('You passed a lastname: %s', $lastname));

        if ($input->getOption('option1')) {
            // ...
        }

        $happy = $io->choice("Are you happy with this training?", ['Yes of course!', 'Meh']);
        $io->note("Happiness: ".$happy);

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
