<?php

namespace App\Command;

use App\Entity\BasisArtikel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'import:basis_artikel',
    description: 'Import für die fehlenden Basis Artikel',
)]
class ImportBasisArtikelCommand extends Command
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
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $entityManager = $this->doctrine->getManager();
        $conn = $entityManager->getConnection();

        $queryBuilder = $conn->createQueryBuilder();

        $sql = 'UPDATE artikel inner join basis_artikel on artikel.artikelnummer = basis_artikel.artikel_nummer SET artikel.basis_artikel_id = basis_artikel.id WHERE artikel.basis_artikel_id IS NULL';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $qb = $queryBuilder
            ->select(
                'artikelnummer', 'SUM(menge) as menge'
            )
            ->from('artikel')
            ->orderBy('menge', 'DESC')
            ->groupBy('artikelnummer');


        $stmt = $conn->executeQuery($qb);

        foreach ($stmt->fetchAllAssociative() as $item) {
            $basisArtikel = $entityManager->getRepository(BasisArtikel::class)->findOneBy(['ArtikelNummer' => $item['artikelnummer']]);

            if (is_null($basisArtikel)) {
                $basisArtikel = new BasisArtikel();
                $basisArtikel->setArtikelNummer($item['artikelnummer']);
                $basisArtikel->setMenge($item['menge']);
                $basisArtikel->setAuftraege(0);
            } else {
                $basisArtikel->setMenge($item['menge']);
            }
            $this->em->persist($basisArtikel);
            $this->em->flush();

            /*$ArtikelListe = $entityManager->getRepository(Artikel::class)->findBy(['Artikelnummer' => $item['artikelnummer'], 'basisArtikel' => null]);
            foreach ($ArtikelListe as $Artikel) {
                echo 'Basis Artikel: '.$basisArtikel->getArtikelNummer().' Bezeichnung:'.$Artikel->getBezeichnung()."\r\n";
                $Artikel->setBasisArtikel($basisArtikel);
                $this->em->persist($Artikel);
                $this->em->flush();
                $i++;
            }*/
        }

        return Command::SUCCESS;
    }
}