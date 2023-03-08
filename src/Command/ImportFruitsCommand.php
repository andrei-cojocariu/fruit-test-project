<?php

namespace App\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use PHPUnit\Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\ImportFruitsService;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[AsCommand(
    name: 'app:import-fruits',
    description: 'Imports Fruits from fruityvice.com.',
)]
class ImportFruitsCommand extends Command
{
    private ImportFruitsService $fruitService;
    private InputInterface $input;
    private OutputInterface $output;

    public function __construct(ImportFruitsService $fruitService)
    {
        $this->fruitService = $fruitService;
        parent::__construct();
    }

    /**
     * @param InputInterface $input
     * @return void
     */
    public function setInput(InputInterface $input): void
    {
        $this->input = $input;
    }

    /**
     * @return InputInterface
     */
    public function getInput(): InputInterface
    {
        return $this->input;
    }

    /**
     * @param OutputInterface $output
     * @return void
     */
    public function setOutput(OutputInterface $output): void
    {
        $this->output = $output;
    }

    /**
     * @return OutputInterface
     */
    public function getOutput(): OutputInterface
    {
        return $this->output;
    }

    protected function configure(): void
    {
//        $this
//            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
//            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    private function firstRun()
    {
        $io = new SymfonyStyle($this->input, $this->output);
        $io->info("There are no fruit stored. Starting import from fruityvice.com \n");
        $fruits = $this->fruitService->getFruits();
        $fruitCount = count($fruits);
        $io->note("Importing $fruitCount fruits:");
        $i = 0;
        foreach ($io->progressIterate($fruits) as $fruit) {
            $io->note("Importing fruit: " . $fruit["name"]);
            $this->fruitService->saveFruit($fruit);
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->setInput($input);
        $this->setOutput($output);

        if ($this->fruitService->countStoredFruits() === 0) {
            $this->firstRun();
        }

        return Command::SUCCESS;
    }
}
