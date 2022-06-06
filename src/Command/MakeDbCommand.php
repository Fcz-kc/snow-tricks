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
    name: 'make:db',
    description: 'Add a short description for your command',
)]
class MakeDbCommand extends Command
{

    protected function configure(): void
    {
        $this
            ->setDescription('Remove tables, create tables and make fixtures')
            ->setHelp('This command allows you to drop all the tables, create the tables and load the fixtures');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $hasErrorOnDelete = true;
        $output->writeln('<question>Try removing database...</question>');
        $removeDb = shell_exec('php bin/console d:d:d --force --if-exists');
        // When database doesn't exist
        if (
            $removeDb == "Database `snow-tricks` for connection named default doesn't exist. Skipped.\n"
        ) {
            shell_exec('php bin/console d:d:c');
            $hasErrorOnDelete = false;
        } else if (
            $removeDb !== null &&
            !$removeDb ||
            $removeDb == "Dropped database `snow-tricks` for connection named default\n"
        ) {
            $hasErrorOnDelete = false;
        }

        if (!$hasErrorOnDelete) {
            $output->writeln('<info>The database have been successfully removed</info>');
            $output->writeln('<question>Try creating database...</question>');

            $createDb = shell_exec('php bin/console d:d:c');

            if ($createDb !== null) {
                $output->writeln('<info>The database have been successfully created</info>');
                $output->writeln('<question>Try creating tables...</question>');

                // The d:s:u commande will always works cause a new datababse is just created
                shell_exec('php bin/console d:s:u --force');

                $output->writeln('<info>The tables have been successfully created !</info>');
                $output->writeln('<question>Try loading fixtures...</question>');

                $loadFixtures = shell_exec('php bin/console d:f:l --append');
                // This is the result of the command when the fixtures are successfully loaded. If something changes into the string, the condition will be false
                $responseOnSuccessFixtures =  "\n   > loading App\DataFixtures\VideoFixtures\n   > loading App\DataFixtures\AppFixtures\n";

                if ($loadFixtures == $responseOnSuccessFixtures || $loadFixtures !== null && !$loadFixtures) {
                    $output->writeln('<info>The fixtures have been successfully loaded !</info>');
                   return Command::SUCCESS;
                } else {
                    $output->writeln("<comment>Cannot load fixtures !\n<comment><error>" . $loadFixtures . '</error>');
                }
            } else {
                $output->writeln("<comment>Cannot create the database !\n<comment><error>" . $createDb . '</error>');
            }
        } else {
            $output->writeln("<comment>Cannot remove the database !\n<comment><error>" . $removeDb . '</error>');
        }

        return Command::FAILURE;
    }
}
