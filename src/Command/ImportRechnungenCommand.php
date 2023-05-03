<?php

namespace App\Command;

use App\Entity\Rechnung;
use DateTimeImmutable;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'import:rechnungen',
    description: 'Import für die fehlenden Rechnungen',
)]
class ImportRechnungenCommand extends Command
{

    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $connectionParams = [
            'dbname' => 'eazybusiness', //'%env(resolve:DATABASE_URL)%'
            'user' => 'reader',
            'password' => 'reader',
            'host' => '192.168.2.80\VJTL',
            'driver' => 'pdo_sqlsrv',
        ];
        $conn = DriverManager::getConnection($connectionParams);

        $queryBuilder = $conn->createQueryBuilder();

        $entityManager = $this->doctrine->getManager();

        $letzteRechnung = $entityManager->getRepository(Rechnung::class)->findTheLatest();

        // dump($letzteRechnung->getId());

        if (is_object($letzteRechnung)) $date = $letzteRechnung->getCreatedAt()->format('Y-m-d H:i:s.u'); else $date = '1970-01-01 00:00:00.000';

        if ($date != '1970-01-01 00:00:00.000') $date = substr($date, 0, -3);
        $date = str_replace('.000', '.999', $date);


        $qb = $queryBuilder
            ->select(
                'tBes.kAuftrag AS id, tBes.cAuftragsNr AS bestellnummer, tRech.cRechnungsNr AS rechnungsnummer, 
	            tLief.cAnrede AS anrede, tLief.cvorname AS vorname, tLief.cname AS nachname, tLief.cStrasse AS strasse, 
	            tLief.cPLZ AS plz, tLief.cOrt AS ort, tLief.cLand as land, tLief.cTel AS tel, tLief.cMail AS email, 
	            tKun.cKundenNr AS kd_nr, tsh.cName AS webshop, tPlatt.cname AS plattform, tRech.dErstellt as dErstellt'
            )
            ->from('trechnung', 'tRech')
            ->leftJoin('tRech', 'Verkauf.tAuftragRechnung', 'tar', 'tar.krechnung = tRech.kRechnung')
            ->leftJoin('tar', 'Verkauf.tAuftrag', 'tBes', 'tBes.kauftrag = tar.kauftrag')
            ->leftJoin('tBes', 'Verkauf.tAuftragAdresse', 'tLief', 'tLief.kauftrag = tBes.kauftrag AND tLief.ntyp = 0')
            ->leftJoin('tBes', 'tkunde', 'tKun', 'tKun.kKunde = tBes.kKunde')
            ->leftJoin('tBes', 'tPlattform', 'tPlatt', 'tPlatt.nPlattform = tBes.kPlattform')
            ->leftJoin('tBes','tshop', 'tsh', 'tsh.kshop = tBes.kshop')
            ->where('tRech.cRechnungsNr IS NOT NULL')->andWhere('tPlatt.cname NOT IN (\'JTL-Wawi\',\'LS-POS\')')
            ->andWhere('CAST(tRech.dErstellt AS DATETIME) > CONVERT(DATETIME, \'' . $date . '\',21)')
            ->andWhere("nStorno = 'false'")
            ->orderBy('dErstellt', 'ASC');

        $stmt = $conn->executeQuery($qb);

        foreach ($stmt->fetchAllAssociative() as $item) {
            $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s.u', $item['dErstellt']);
            if (!is_null($item['kd_nr'])) {
                echo $item['rechnungsnummer'] . "\r\n";
                $rechnung = new Rechnung();
                $rechnung->setId($item['id']);
                $rechnung->setBestellNummer($item['bestellnummer']);
                $rechnung->setRechnungsnummer($item['rechnungsnummer']);
                $rechnung->setAnrede($item['anrede']);
                $rechnung->setVorname($item['vorname']);
                $rechnung->setNachname($item['nachname']);
                $rechnung->setStrasse($item['strasse']);
                $rechnung->setPLZ($item['plz']);
                $rechnung->setOrt($item['ort']);
                $rechnung->setLand($item['land']);
                $rechnung->setTel($item['tel']);
                $rechnung->setEmail($item['email']);
                $rechnung->setKdNr($item['kd_nr']);
                $rechnung->setWebshop($item['webshop']);
                $rechnung->setPlattform($item['plattform']);
                $rechnung->setCreatedAt($date);

                $entityManager->persist($rechnung);
                $entityManager->flush();
            }
        }

        return Command::SUCCESS;
    }
}
