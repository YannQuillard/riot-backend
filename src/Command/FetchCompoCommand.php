<?php

namespace App\Command;

use App\Service\CompositionService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FetchCompoCommand extends Command
{
    protected static $defaultName = 'fetch:compo';
    protected static $defaultDescription = 'Add a short description for your command';

    /**
     * @var CompositionService
     */
    private $compositionService;

    public function __construct(CompositionService $compositionService) {
        parent::__construct();
        $this->compositionService = $compositionService;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        $riotChallenger = $this->compositionService->getChallengers();
        $this->compositionService->getMatchsInfo($riotChallenger);

        $io->writeln('Compositions fetched successfully');

        return Command::SUCCESS;
    }
}
