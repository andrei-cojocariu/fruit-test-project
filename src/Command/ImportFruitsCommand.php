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
    name: 'app:import',
    description: 'Imports Fruits from fruityvice.com.',
)]
class ImportFruitsCommand extends Command
{
    private ImportFruitsService $fruitService;
    private InputInterface $input;
    private OutputInterface $output;
    private OutputInterface $io;

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

    /**
     * @return SymfonyStyle
     */
    public function getIo(): SymfonyStyle
    {
        return $this->io;
    }

    /**
     * @param SymfonyStyle $io
     */
    public function setIo(SymfonyStyle $io): void
    {
        $this->io = $io;
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
        $this->io->info("There are no fruit stored. Starting import from fruityvice.com \n");
        $fruits = $this->fruitService->importFruits();
        $fruitCount = count($fruits);
        $this->io->note("Importing $fruitCount fruits:");
        $i = 0;
        foreach ($this->io->progressIterate($fruits) as $fruit) {
            $this->io->note("Importing fruit: " . $fruit["name"]);
            $this->fruitService->saveFruit($fruit);
        }
    }

    private function showMenu()
    {
        $this->output->write(sprintf("\033\143"));
        $this->io->table(
            $this->fruitService->showCommandMenu(),
            []
        );
    }

    private function showFruits()
    {
        $this->io->table(
            $this->fruitService->getFruitTableHeader(),
            $this->fruitService->getFruitTableData()
        );
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
        $this->setIO(new SymfonyStyle($this->input, $this->output));

        if ($this->fruitService->countStoredFruits() === 0) {
            $this->firstRun();
        }
        $this->showMenu();
        $this->showFruits();

        return Command::SUCCESS;
    }
}
