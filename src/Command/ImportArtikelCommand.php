<?php

namespace App\Command;

use App\Entity\Artikel;
use App\Entity\Rechnung;
use DateInterval;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;

#[AsCommand(
    name: 'import:artikel',
    description: 'Import für die fehlenden Artikel',
)]
class ImportArtikelCommand extends Command
{
    private ManagerRegistry $doctrine;
    private EntityManagerInterface $em;

    public function __construct(ManagerRegistry $doctrine, EntityManagerInterface $em)
    {
        $this->doctrine = $doctrine;
        $this->em = $em;
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
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $connectionParams = [
            'dbname' => 'eazybusiness',
            'user' => 'reader',
            'password' => 'reader',
            'host' => '192.168.2.80\VJTL',
            'driver' => 'pdo_sqlsrv',
        ];
        $conn = DriverManager::getConnection($connectionParams);

        $queryBuilder = $conn->createQueryBuilder();

        $entityManager = $this->doctrine->getManager();

        $letzteArtikel = $entityManager->getRepository(Artikel::class)->findTheLatest();

        //echo 'Letzter Artikel: ' . (is_object($letzteArtikel)?$letzteArtikel->getId():0). "\r\n";

        $qb = $queryBuilder
            ->select(
                "tbpos.kAuftragPosition AS id, 
		        tRech.cRechnungsNr AS rechungsnummer, 
		        (CASE WHEN tArt.cISBN = '' AND tArt.cHAN = '' THEN tArt.cArtNr WHEN tArt.cISBN = '' THEN tArt.cHAN ELSE tArt.cISBN END) AS artikelnummer, 
		        tbpos.cName as bezeichnung, 
		        tbpos.fAnzahl as menge, 
		        trech.derstellt AS zeitstempel"
            )
            ->from('trechnung', 'tRech')
            ->leftJoin('tRech', 'Verkauf.tAuftragRechnung', 'tar', 'tar.krechnung = tRech.kRechnung')
            ->leftJoin('tar', 'Verkauf.tAuftrag', 'tBes', 'tBes.kauftrag = tar.kauftrag')
            ->leftJoin('tBes', 'Verkauf.tAuftragPosition', 'tbpos', 'tbpos.kauftrag = tBes.kauftrag')
            ->leftJoin('tbpos', 'tArtikel', 'tart', 'tbpos.cartnr = tArt.cartnr')
            ->leftJoin('tBes', 'tPlattform', 'tPlatt', 'tPlatt.nPlattform = tBes.kPlattform')
            ->where('tRech.cRechnungsNr IS NOT NULL')
            ->andWhere("tPlatt.cname NOT IN ('JTL-Wawi','LS-POS')")->andWhere("tart.cartnr NOT LIKE 'A-%'")
            ->andWhere('tArt.cartnr IS NOT NULL')
            ->andWhere('tArt.chan IS NOT NULL')
            ->andWhere('tArt.cisbn IS NOT NULL')
            ->andWhere("nStorno = 'false'")
            ->andWhere("tbpos.cName NOT LIKE '%Aufkleber%'")->andWhere('tart.kArtikel NOT IN (SELECT kartikel FROM tStueckliste)')->andWhere('tArt.kHersteller  IN (27, 13, 638, 635, 587, 636, 36, 54, 52, 637, 472, 35, 51, 47, 482, 65, 665)')
            ->andWhere("CONVERT(datetime, tRech.dErstellt, 20) > CONVERT(datetime, '".(is_object($letzteArtikel)?$letzteArtikel->getzeitstempel()->add(new DateInterval('PT1S'))->format('Y-m-d H:i:s'):'1970-01-01 00:00:00')."', 20)")
            ->orderBy('zeitstempel', 'ASC');

        $stmt = $conn->executeQuery($qb);

        $items = $stmt->fetchAllAssociative();

        $progressBar = new ProgressBar($output, $stmt->rowCount());
        $progressBar->setFormat('debug');
        $progressBar->setMaxSteps($stmt->rowCount());
        $progressBar->start();

        $entityManager = $this->doctrine->getManager();

        foreach ($items as $item) {

            $progressBar->advance();
            $rechnung = $entityManager->getRepository(Rechnung::class)->findOneBy(['Rechnungsnummer' => $item['rechungsnummer']]);

            if (!is_null($item['artikelnummer']) && !is_null($rechnung) &&
                !in_array($item['artikelnummer'], ['4444', '9999', '5-BBS', 'BBS', 'Pfand', 'S23', 'S25', 'S28', 'S5', 'S9', 'S10', 'S14', 'S3', 'US1', 'S16', 'S15', 'S12', 'BK50250P', 'S21', 'BK50400P', 'S20', 'BMO'])) {

                $artikel = $entityManager->getRepository(Artikel::class)->findOneBy(['id' => $item['id']]);

                if (is_object($artikel)) {
                    $artikel->setRechnung($rechnung);
                } else {

                    //echo $item['artikelnummer'] . "\r\n";
                    $artikel = new Artikel();
                    $artikel->setId($item['id']);
                    $artikel->setRechnung($rechnung);
                    $artikel->setArtikelnummer($item['artikelnummer']);
                    $artikel->setBezeichnung($item['bezeichnung']);
                    $artikel->setMenge($item['menge']);
                    $artikel->setzeitstempel($item['zeitstempel']);

                }
                $this->em->persist($artikel);
                $this->em->flush();
            }
        }

        $progressBar->finish();

        return Command::SUCCESS;
    }
}