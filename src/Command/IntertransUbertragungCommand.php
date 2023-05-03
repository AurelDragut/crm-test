<?php
namespace App\Command;

use Doctrine\DBAL\Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use phpseclib3\Net\SFTP;
use phpseclib3\Net\SSH2;


#[AsCommand(
    name: 'uebertragung:intertrans',
    description: 'Übertragung für Intertrans',
)]
class IntertransUbertragungCommand extends Command
{

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        define('NET_SSH2_LOGGING', SSH2::LOG_SIMPLE);
        define('NET_SFTP_LOGGING', SFTP::LOG_SIMPLE);
        define('NET_SSH2_LOG_REALTIME_FILENAME', 'log.txt');

        $finder = new Finder();

        $finder->files()->in('C:\Users\Administrator\Desktop\test');

        $sftp = new SFTP('dedivirt2812.your-server.de');
        $sftp_login = $sftp->login('batteq', '4wcAas6k11kD5nw8');

        $zielOrdner = 'public_html/datei';

        $changedir = $sftp->chdir($zielOrdner);

        if($sftp_login) {
            if ($changedir !== false) {
                foreach ($finder as $file) {
                    $success = $sftp->put($file->getFilename(), $file, SFTP::SOURCE_LOCAL_FILE);
                    if ($success) {
                        echo 'Die Datei ' . $file->getFilename() . " wurde erfolgreich übertragen!\r\n";
                        if (unlink($file)) echo 'Die Datei ' . $file->getFilename() . " wurde erfolgreich gelöscht!\r\n";
                    }
                    else
                        echo 'Beim Übertragen der Datei ' . $file->getFilename() . " ist ein Fehler aufgetreten!\r\n";
                }
            } else {
                echo "der Zielordner $zielOrdner ist nicht zugänglich";
            }
        }

        return Command::SUCCESS;
    }
}