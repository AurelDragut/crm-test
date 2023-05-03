<?php
namespace App\Controller;

use App\Entity\BatterieRegal;
use App\Entity\Frage;
use App\Entity\Haendler;
use App\Entity\Menu;
use App\Entity\Seite;
use App\Entity\Slide;
use DateTime;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;
use phpseclib3\Net\SFTP;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use function Symfony\Component\String\s;


class PagesController extends AbstractController
{

    private ManagerRegistry $registry;
    private array $menus;
    private $em;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->registry = $registry;
        $entityManager = $this->registry->getManager();
        $this->menus = $entityManager->getRepository(Menu::class)->findAll();
    }

    #[Route('/', name: 'app_homepage')]
    public function index(): Response
    {
        $entityManager = $this->registry->getManager();

        $seite = $entityManager->getRepository(Seite::class)->findOneBy(['Titel' => 'Startseite']);

        return $this->render('seite.html.twig', ['seite' => $seite, 'menu' => $this->menus]);
    }

    #[Route('/reklamation', name: 'reklamation')]
    public function reklamation(): Response
    {
        $entityManager = $this->registry->getManager();
        $fragen = $entityManager->getRepository(Frage::class)->findAll();
        return $this->render('reklamation.html.twig', ['fragen' => $fragen, 'menu' => $this->menus]);
    }

    #[Route('/impressum', name: 'impressum')]
    public function impressum(): Response
    {
        $entityManager = $this->registry->getManager();
        $seite = $entityManager->getRepository(Seite::class)->findOneBy(['Titel' => 'Impressum']);
        return $this->render('seite.html.twig', ['seite' => $seite, 'menu' => $this->menus]);
    }

    #[Route('/datenschutz', name: 'datenschutz')]
    public function datenschutz(): Response
    {
        $entityManager = $this->registry->getManager();
        $seite = $entityManager->getRepository(Seite::class)->findOneBy(['Titel' => 'Datenschutzerklärung']);
        return $this->render('seite.html.twig', ['seite' => $seite, 'menu' => $this->menus]);
    }

    /**
     * @throws Exception
     */
    #[Route('/ebaypreisb61100', name: 'ebayPreisB61100')]
    public function ebayPreisB61100(): Response
    {
        $NowTime = date("H:i");

        $start = strtotime($NowTime);
        $stop = strtotime("23:00");

        $diff = ($stop - $start); //Diff in seconds

        $minutes = $diff / 60;

        session_cache_expire($minutes);
        session_start();
        $now = new DateTime();
        unset($_SESSION['date']);
        if (!isset($_SESSION['date']) || $_SESSION['date'] !== $now->format('Y-m-d')) {
            $_SESSION['date'] = $now->format('Y-m-d');
            $connectionParams = [
                'dbname' => 'eazybusiness',
                'user' => 'reader',
                'password' => 'reader',
                'host' => '192.168.2.80\VJTL',
                'driver' => 'pdo_sqlsrv',
            ];
            $conn = DriverManager::getConnection($connectionParams);

            $queryBuilder = $conn->createQueryBuilder();

            $sql = 'UPDATE tPreisDetail 
	SET tPreisDetail.fNettoPreis = T2.min_ebay_preis FROM (SELECT tArtikel.kartikel, tPreis.kpreis AS kpreis, (FLOOR(MIN(ebay_item.StartPrice) * 0.95)-0.10) / 1.19 AS min_ebay_preis FROM tArtikelShop 
	INNER JOIN tArtikel ON tArtikel.kartikel = tArtikelShop.kArtikel 
	INNER JOIN tStueckliste ON tArtikel.kstueckliste = tStueckliste.kstueckliste
	INNER JOIN tArtikel tartstuck ON tartstuck.kartikel = tStueckliste.kartikel 
	INNER JOIN tStueckliste tstuck ON tstuck.kArtikel = tartstuck.kartikel
	INNER JOIN tArtikel tstuckart ON tstuckart.kStueckliste = tstuck.kstueckliste AND tstuckart.khersteller = tArtikel.kHersteller AND tstuckart.cartnr LIKE CONCAT(\'%\', tArtikel.cartnr,\'%\')
	INNER JOIN ebay_item ON tstuckart.cartnr = ebay_item.sku 
	INNER JOIN tPreis ON tPreis.kArtikel = tArtikel.kartikel AND tPreis.kshop = 28
	WHERE CAST(ebay_item.endtime AS DATE) >= CAST( GETDATE() AS Date) 
		AND tArtikel.khersteller NOT IN (27, 638, 628, 587, 482, 617, 634, 611, 665, 633)
		AND tartikel.cartnr NOT LIKE (\'%-A\') AND tartikel.cartnr NOT LIKE \'BSA%\' AND tArtikel.cArtNr NOT LIKE \'%-BSA US\' AND tArtikel.cArtNr NOT LIKE \'%-AT\' AND tartstuck.cartnr NOT IN (\'A-P15\', \'A-BS\', \'A-P4\')
		AND tArtikel.cartnr NOT LIKE (\'%-shop\') 
		AND tartstuck.cartnr LIKE \'A-%\'
GROUP BY tArtikel.kartikel, tPreis.kpreis) as T2 WHERE tPreisDetail.kpreis IN (SELECT kpreis FROM tPreis WHERE kshop = 28) AND tPreisDetail.kPreis = T2.kpreis';

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, 'B61100');
            $stmt->executeQuery();

            $qb = $queryBuilder
                ->select(
                    'tArt.kartikel, tArt.cartnr, min(ebay.StartPrice) AS ebayPrice, min(amazon.fprice) AS amazonPreis, CAST(min(tpdet.fNettoPreis) * 1.19 as numeric(10,2)) as shop_brutto_preis, MIN(amazon.casin1) as ASIN, MIN(ebay.ItemID) AS ebayID')
                ->from('tArtikel', 'tart')
                ->innerJoin('tart', 'tStueckliste', 'tst', 'tst.kstueckliste = tart.kstueckliste')
                ->innerJoin('tart', 'tArtikel', 'tartstuck', 'tst.kartikel = tartstuck.kartikel')
                ->innerJoin('tart', 'tStueckliste', 'tstuckart', "tstuckart.kartikel = tartstuck.kartikel")
                ->innerJoin('tart', 'tArtikel', 'tartstuckart', "tstuckart.kstueckliste = tartstuckart.kstueckliste AND tartstuckart.cartnr LIKE CONCAT('%', tart.cartnr,'%')")
                ->leftJoin('tart', 'ebay_item', 'ebay', 'ebay.kartikel = tartstuckart.kartikel AND (CAST(ebay.endtime AS DATE) >= CAST( GETDATE() AS Date))')
                ->leftJoin('tart', 'pf_amazon_angebot', 'amazon', 'tartstuckart.cartnr = amazon.csellersku')
                ->leftJoin('tart', 'tpreis', 'tpreis', 'tpreis.kartikel = tart.kartikel')
                ->leftJoin('tart', 'tpreisdetail', 'tpdet', 'tpdet.kpreis = tpreis.kpreis')
                ->andWhere("tartstuck.cartnr LIKE 'A-%' AND tartstuck.cartnr != 'A-BS'")
                ->andWhere('tartstuckart.khersteller = tArt.khersteller')
                ->andWhere('(ebay.kartikel IS NOT NULL) OR (amazon.csellersku IS NOT NULL)')
                ->andWhere('tpreis.kshop = 28')
                ->groupBy('tArt.kartikel', 'tArt.cartnr');

            $stmt = $conn->executeQuery($qb);

            //var_dump($qb->getSQL());

            $items = $stmt->fetchAllAssociative();

            //echo $qb->getSQL();
            echo '<hr />';
            echo 'sku: ', $items[0]['cartnr'], '<br />';
            echo 'shop_brutto_preis: ', (double)$items[0]['shop_brutto_preis'], '<br />';
            echo 'amazon_preis: ', (double)$items[0]['amazonPreis'], '<br />';
            echo 'min_ebay_price: ', (double)$items[0]['ebayPrice'], '<br />';

            $sftp = new SFTP('dedivirt2812.your-server.de', 22);
            $login_result = $sftp->login('battwb_0', 'ftkQSivQgmvFTE86');

            if (!$login_result) {
                // PHP will already have raised an E_WARNING level message in this case
                die("can't login");
            }

            $sftp->chdir('templates/NOVAChild');
            //$files = $sftp->nlist();

            for ($i = 0; $i < count($items); $i++) {
                $artikelNummer = $items[$i]['cartnr'];
                $_SESSION['preise'][$artikelNummer]['shop_brutto_preis'] = (double)$items[$i]['shop_brutto_preis'];
                $_SESSION['preise'][$artikelNummer]['amazon_preis'] = (double)$items[$i]['amazonPreis'];
                $_SESSION['preise'][$artikelNummer]['ASIN'] = $items[$i]['ASIN'];
                $_SESSION['preise'][$artikelNummer]['min_ebay_price'] = (double)$items[$i]['ebayPrice'];
                $_SESSION['preise'][$artikelNummer]['ebayID'] = $items[$i]['ebayID'];

                $amazonPreis = str_replace(',', '.', $_SESSION['preise'][$artikelNummer]['amazon_preis']);
                $amazonPreis = (double)$amazonPreis + 4.90;

                if ($amazonPreis > $_SESSION['preise'][$artikelNummer]['min_ebay_price']) {
                    $shopPreisProzent = str_replace('.', '', $_SESSION['preise'][$artikelNummer]['shop_brutto_preis'] / ($amazonPreis / 100));
                    $ebayPreisProzent = str_replace('.', '', $_SESSION['preise'][$artikelNummer]['min_ebay_price'] / ($amazonPreis / 100));
                    $amazonPreisProzent = 99;
                    $ebayAmpelFarbe = '#508cbe';
                    $arrowColorEbay = 'white';
                    $amazonAmpelFarbe = '#8eafca';
                    $arrowColorAmazon = 'white';

                } elseif ($amazonPreis < $_SESSION['preise'][$artikelNummer]['min_ebay_price']) {
                    $shopPreisProzent = str_replace('.', '', $_SESSION['preise'][$artikelNummer]['shop_brutto_preis'] / ($_SESSION['preise'][$artikelNummer]['min_ebay_price'] / 100));
                    $ebayPreisProzent = 99;
                    $amazonPreisProzent = str_replace('.', '', $amazonPreis / ($_SESSION['preise'][$artikelNummer]['min_ebay_price'] / 100));
                    $ebayAmpelFarbe = '#8eafca';
                    $arrowColorEbay = 'white';
                    $amazonAmpelFarbe = '#508cbe';
                    $arrowColorAmazon = 'white';
                } else {
                    $shopPreisProzent = str_replace('.', '', $_SESSION['preise'][$artikelNummer]['shop_brutto_preis'] / ($_SESSION['preise'][$artikelNummer]['min_ebay_price'] / 100));
                    $ebayPreisProzent = 99;
                    $amazonPreisProzent = 99;
                    $ebayAmpelFarbe = '#8eafca';
                    $arrowColorEbay = 'white';
                    $amazonAmpelFarbe = '#8eafca';
                    $arrowColorAmazon = 'white';
                }
                if ($artikelNummer == 'S60044') {
                    $content[$artikelNummer] = '<div class="container">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <table id="bar-example-17" class="charts-css bar show-labels show-primary-axis show-data-axes data-spacing-30">
                                                <tbody>
                                                <tr>
                                                    <th scope="row" class="text-right" style="padding-right:1rem; font-size: 16px;"><a href="https://www.batteriescout.de/index.php?jtl_token=bf1cf55769157d2318284efce1c110f9d90f60b10345451e4127cfc75e6842ca&qs=' . $artikelNummer . '&search=" target="_blank">BatterieScout.de</a></th>
                                                    <td style="color: #fff; background-color:#004682; padding-right:1rem; font-size: 20px; --size:0.' . $shopPreisProzent . '">
                                                        <div class="arrowSign">
                                                            <div class="line-white"></div>
                                                            <i class="arrow-white right"></i>
                                                        </div>
                                                    ' . number_format($_SESSION['preise'][$artikelNummer]['shop_brutto_preis'], 2) . '&euro;</td>
                            
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="text-right" style="padding-right:1rem; font-size: 16px;"><a href="https://www.ebay.de/itm/' . $_SESSION['preise'][$artikelNummer]['ebayID'] . '" target="_blank">Ebay.de</a></th>
                                                    <td style="color: #fff; background-color:' . $ebayAmpelFarbe . '; padding-right:1rem; font-size: 20px; --size:0.' . $ebayPreisProzent . '">
                                                        <div class="arrowSign">
                                                            <div class="line-' . $arrowColorEbay . '"></div>
                                                            <i class="arrow-' . $arrowColorEbay . '"></i>
                                                        </div>
                                                    ' . $_SESSION['preise'][$artikelNummer]['min_ebay_price'] . '&euro;</td>
                                                </tr>';
                    if ($_SESSION['preise'][$artikelNummer]['ASIN'] != "") {
                        $content[$artikelNummer] .= '<tr>
                                                    <th scope = "row" class="text-right" style = "padding-right:1rem; font-size: 16px;" ><a href = "https://www.amazon.de/gp/product/' . $_SESSION['preise'][$artikelNummer]['ASIN'] . '" target = "_blank" > Amazon . de</a ></th >
                                                    <td style = "color: #fff; background-color:' . $amazonAmpelFarbe . '; padding-right:1rem; font-size: 20px; --size:0.' . $amazonPreisProzent . '" >
                                                        <div class="arrowSign" >
                                                            <div class="line-' . $arrowColorAmazon . '" ></div >
                                                            <i class="arrow-' . $arrowColorAmazon . '" ></i >
                                                        </div >
                                                    ' . number_format($amazonPreis, 2, ',', ' ') . '&euro; <!--(' . $_SESSION['preise'][$artikelNummer]['amazon_preis'] . ' &euro;+4,90 &euro;)--></td >
                                                </tr>';
                    }
                    $content[$artikelNummer] .= '</tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <div class="rabatt">';
                    echo $amazonPreis = $_SESSION['preise'][$artikelNummer]['amazon_preis'] + 4.90;
                    echo '<hr />';
                    echo $_SESSION['preise'][$artikelNummer]['min_ebay_price'];
                    echo '<hr />';
                    echo $grosstePreis = max($_SESSION['preise'][$artikelNummer]['min_ebay_price'], $amazonPreis);
                    echo '<hr />';
                    echo $prozent = 100 - ($_SESSION['preise'][$artikelNummer]['shop_brutto_preis'] / ($grosstePreis / 100));
                    $content[$artikelNummer] .= '<div class="inhalt-rabatt"><span class="rabatt-preis">' . number_format($prozent, 0) . '</span><span class="rabatt-prozent">%</span></div>';
                    $content[$artikelNummer] .= '
                                </div>
                                <a href="https://www.batteriescout.de/index.php?jtl_token=bf1cf55769157d2318284efce1c110f9d90f60b10345451e4127cfc75e6842ca&qs=' . $artikelNummer . '&search=" target="_blank">
                                    <img src="/bilder/S60044.jpg" class="img-fluid img-responsive rabattBild" alt="S60044 SIGA Autobatterie 12V 100AH 850A/EN">
                                </a>    
                            </div>
                        </div>
                    </div>';
                    $sftp->put('preisVergleich.tpl', $content['S60044']);
                }

                $grosstePreis = max($_SESSION['preise'][$artikelNummer]['min_ebay_price'], $amazonPreis);
                $prozent = 100 - ($_SESSION['preise'][$artikelNummer]['shop_brutto_preis'] / ($grosstePreis / 100));

                $content[$artikelNummer] = '<div class="preisVergleich">
                                    <div class="row">
                                        <h3><span class="pvrabatt">' . number_format($prozent, 0) . '%</span> ERSPARNIS</h3>
                                    </div>
                                    <table id="bar-example-17" class="charts-css column show-labels show-primary-axis show-data-axes data-spacing-20">
                                                <tbody>
                                                <tr>
                                                    <th scope="row" class="text-right" style="padding-right:1rem; font-size: 16px;">BatterieScout.de</th>
                                                    <td style="color: #fff; background-color:#004682; padding-right:1rem; font-size: 20px; --size:0.' . $shopPreisProzent . '">
                                                        ' . number_format($_SESSION['preise'][$artikelNummer]['shop_brutto_preis'], 2) . '&euro;
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="text-right" style="padding-right:1rem; font-size: 16px;"><a href="https://www.ebay.de/itm/' . $_SESSION['preise'][$artikelNummer]['ebayID'] . '" target="_blank">Ebay.de</a></th>
                                                    <td style="color: #fff; background-color:' . $ebayAmpelFarbe . '; padding-right:1rem; font-size: 20px; --size:0.' . $ebayPreisProzent . '">
                                                        ' . $_SESSION['preise'][$artikelNummer]['min_ebay_price'] . '&euro;
                                                    </td>
                                                </tr>';
                if ($_SESSION['preise'][$artikelNummer]['ASIN'] != "") {
                    $content[$artikelNummer] .= '<tr>
                                                    <th scope="row" class="text-right" style="padding-right:1rem; font-size: 16px;"><a href="https://www.amazon.de/gp/product/' . $_SESSION['preise'][$artikelNummer]['ASIN'] . '" target="_blank">Amazon.de</a></th>
                                                    <td style="color: #fff; background-color:' . $amazonAmpelFarbe . '; padding-right:1rem; font-size: 20px; --size:0.' . $amazonPreisProzent . '">
                                                        <span>' . number_format($amazonPreis, 2, ',', ' ') . '&euro;</span>
                                                    </td>
                                                </tr>';
                }
                $content[$artikelNummer] .= '</tbody>
                                            </table>
                               </div>';
                $sftp->put('preisVergleich/' . $artikelNummer . '.tpl', $content[$artikelNummer]);
            }
        }

        return new Response($content['S60044']);
    }

    /**
     * @throws Exception
     */
    #[Route('/batterienRegale', name: 'batterienRegale')]
    public function batterienRegale(ManagerRegistry $doctrine): Response
    {
        $connectionParams = [
            'dbname' => 'eazybusiness',
            'user' => 'reader',
            'password' => 'reader',
            'host' => '192.168.2.80\VJTL',
            'driver' => 'pdo_sqlsrv',
        ];
        $conn = DriverManager::getConnection($connectionParams);

        $sql = "SELECT REPLACE(tArtikel.cartnr,'-KS','') AS Artikel, tHersteller.cName AS Hersteller, Batterietechnologie.cWert as Batterietechnologie, cast(replace(replace(kapazitaet.cwert,'ah',''),',','.') AS DECIMAL(5,1)) as Kapazität, cast(replace(Kaltstartstrom.cwert,'A/EN','') as INTEGER) AS Kaltstartstrom, Mass.cwert AS Maße, Standard.cwert AS Standard, round(tArtikel.fVKNetto * 1.19, 2) AS Preis
	FROM tArtikel
	INNER JOIN tStueckliste ON tStueckliste.kStueckliste = tArtikel.kstueckliste
	INNER JOIN tArtikel basis ON basis.kArtikel = tStueckliste.kartikel AND 
	(basis.cArtNr LIKE 'A-SEU%' OR 
	basis.cArtNr LIKE 'A-S[0-9]%' OR 
	basis.cArtNr IN ('A-BA95601','A-BA95751') OR 
	basis.cArtNr LIKE 'A-SA[0-9]%' OR 
	basis.cArtNr LIKE 'A-SAGM%' OR 
	basis.cArtNr LIKE 'A-ST[0-9]%' OR 
	basis.cArtNr LIKE 'A-GEL%' OR 
	basis.cArtNr LIKE 'A-SUSA%' OR 
	basis.cArtNr LIKE 'A-SSMF%' OR 
	basis.cArtNr LIKE 'A-SEFB%' OR 
	basis.cArtNr LIKE 'A-AKKU%' OR 
	basis.cArtNr LIKE 'A-BLAKKU%' OR
	basis.cArtNr LIKE 'A-LS%' OR
	basis.cArtNr LIKE 'A-0[1-9]%' OR
	basis.cArtNr LIKE 'A-SB%' OR
	basis.cArtNr LIKE 'A-AGME%' OR
	basis.cArtNr LIKE 'A-BLLI%' OR
	basis.cArtNr LIKE 'A-T[1-9]%' OR   
	basis.cArtNr LIKE 'A-slkw%' OR
	basis.cArtNr LIKE 'A-AGME%' OR   
	basis.cArtNr LIKE 'A-STAGM%')
	left JOIN (SELECT tArtikelMerkmal.kArtikel, tMerkmalsprache.cName, tMerkmalWertSprache.cWert FROM tArtikelMerkmal
	INNER JOIN tMerkmalSprache ON tMerkmalSprache.kMerkmal = tArtikelMerkmal.kMerkmal AND tMerkmalSprache.cName = 'Batterietechnologie'
	INNER JOIN tMerkmalWert ON tMerkmalWert.kMerkmal = tArtikelMerkmal.kMerkmal AND tMerkmalWert.kMerkmalWert = tArtikelMerkmal.kMerkmalWert
	INNER JOIN tMerkmalWertSprache ON tMerkmalWertSprache.kMerkmalWert =  tMerkmalWert.kMerkmalWert) Batterietechnologie ON Batterietechnologie.kartikel = tArtikel.kartikel
	left JOIN (SELECT tArtikelMerkmal.kArtikel, tMerkmalsprache.cName, tMerkmalWertSprache.cWert FROM tArtikelMerkmal
	INNER JOIN tMerkmalSprache ON tMerkmalSprache.kMerkmal = tArtikelMerkmal.kMerkmal AND tMerkmalSprache.cName = 'Kapazität'
	INNER JOIN tMerkmalWert ON tMerkmalWert.kMerkmal = tArtikelMerkmal.kMerkmal AND tMerkmalWert.kMerkmalWert = tArtikelMerkmal.kMerkmalWert
	INNER JOIN tMerkmalWertSprache ON tMerkmalWertSprache.kMerkmalWert =  tMerkmalWert.kMerkmalWert) kapazitaet ON kapazitaet.kartikel = tArtikel.kartikel
	left JOIN (SELECT tArtikelMerkmal.kArtikel, tMerkmalsprache.cName, tMerkmalWertSprache.cWert FROM tArtikelMerkmal
	INNER JOIN tMerkmalSprache ON tMerkmalSprache.kMerkmal = tArtikelMerkmal.kMerkmal AND tMerkmalSprache.cName = 'Kaltstartstrom'
	INNER JOIN tMerkmalWert ON tMerkmalWert.kMerkmal = tArtikelMerkmal.kMerkmal AND tMerkmalWert.kMerkmalWert = tArtikelMerkmal.kMerkmalWert
	INNER JOIN tMerkmalWertSprache ON tMerkmalWertSprache.kMerkmalWert =  tMerkmalWert.kMerkmalWert) Kaltstartstrom ON Kaltstartstrom.kartikel = tArtikel.kartikel
	left JOIN (SELECT tArtikelMerkmal.kArtikel, tMerkmalsprache.cName, tMerkmalWertSprache.cWert FROM tArtikelMerkmal
	INNER JOIN tMerkmalSprache ON tMerkmalSprache.kMerkmal = tArtikelMerkmal.kMerkmal AND tMerkmalSprache.cName = 'Maße (L x B x H)'
	INNER JOIN tMerkmalWert ON tMerkmalWert.kMerkmal = tArtikelMerkmal.kMerkmal AND tMerkmalWert.kMerkmalWert = tArtikelMerkmal.kMerkmalWert
	INNER JOIN tMerkmalWertSprache ON tMerkmalWertSprache.kMerkmalWert =  tMerkmalWert.kMerkmalWert) Mass ON Mass.kartikel = tArtikel.kartikel
	left JOIN (SELECT tArtikelMerkmal.kArtikel, tMerkmalsprache.cName, tMerkmalWertSprache.cWert FROM tArtikelMerkmal
	INNER JOIN tMerkmalSprache ON tMerkmalSprache.kMerkmal = tArtikelMerkmal.kMerkmal AND tMerkmalSprache.cName = 'Standard'
	INNER JOIN tMerkmalWert ON tMerkmalWert.kMerkmal = tArtikelMerkmal.kMerkmal AND tMerkmalWert.kMerkmalWert = tArtikelMerkmal.kMerkmalWert
	INNER JOIN tMerkmalWertSprache ON tMerkmalWertSprache.kMerkmalWert =  tMerkmalWert.kMerkmalWert) Standard ON Standard.kartikel = tArtikel.kartikel
	INNER JOIN tHersteller ON tHersteller.khersteller = tArtikel.kHersteller
WHERE tArtikel.cartnr LIKE '%-KS' AND tartikel.kHersteller IN (27, 587, 35, 47, 13, 36)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, 'B61100');
        $items = $stmt->executeQuery();

        $entityManager = $doctrine->getManager();

        foreach ($items->fetchAllAssociative() as $batterie) {
            $BatterieRegal = $doctrine->getRepository(BatterieRegal::class)->findOneBy(['Artikelnummer' => $batterie['Artikel']]);
            if (!$BatterieRegal) $BatterieRegal = new BatterieRegal();
            $BatterieRegal->setArtikelnummer($batterie['Artikel']);
            $BatterieRegal->setHersteller($batterie['Hersteller']);
            $BatterieRegal->setBatterietechnologie($batterie['Batterietechnologie']);
            if (isset($batterie['Kapazität'])) $BatterieRegal->setKapazitaet($batterie['Kapazität']);
            if (isset($batterie['Kaltstartstrom'])) $BatterieRegal->setKaltstartstrom($batterie['Kaltstartstrom']);
            $BatterieRegal->setMasse($batterie['Maße']);
            if (isset($batterie['Standard'])) $BatterieRegal->setStandard($batterie['Standard']);
            $BatterieRegal->setPreis($batterie['Preis']);

            $entityManager->persist($BatterieRegal);
            $entityManager->flush();
        }

        return new Response('Done!');
    }

    #[Route('/Regal-{number}', name: 'Regal')]
    public function regalListe($number): Response
    {

        $entityManager = $this->registry->getManager();

        $conn = $this->em->getConnection();
        $sql = "SELECT DISTINCT hersteller, count(Artikelnummer) as Anzahl FROM batterie_regal WHERE Regal = 'Regal $number' group by hersteller order by Anzahl desc";
        $stmt = $conn->prepare($sql);

        $Hersteller = $stmt->executeQuery()->fetchAllAssociative();

        $content = '<!doctype html>
            <html lang="en">
                <head>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
                    <link rel="stylesheet" href="/css/regale.css" />
                    <title>Regale</title>
                </head>
            <body>
                <div class="container-fluid m-0" style="background-image:url(\'/images/Regal.jpg\'); width:1920px; height: 1080px; position: relative">
                    <div class="row">';

        foreach ($Hersteller as $key => $value) {

            $batteries = $entityManager->getRepository(BatterieRegal::class)->findBy(['Regal' => 'Regal ' . $number, 'Hersteller' => $value['hersteller']], ['Kapazitaet' => 'ASC']);

            if ($number == 3) $pages = ceil(count($batteries) / 10);
            elseif ($number == 5 and $key > 1) $pages = ceil(count($batteries) / 4);
            else $pages = ceil(count($batteries) / 16);

            for ($i = 0; $i < $pages; $i++) {
                if ($number == 3) $batteries = $entityManager->getRepository(BatterieRegal::class)->findBy(['Regal' => 'Regal ' . $number, 'Hersteller' => $value['hersteller']], ['Kapazitaet' => 'ASC', 'Kaltstartstrom' => 'ASC'], 10, $i * 10);
                elseif ($number == 5 and $key > 1) $batteries = $entityManager->getRepository(BatterieRegal::class)->findBy(['Regal' => 'Regal ' . $number, 'Hersteller' => $value['hersteller']], ['Kapazitaet' => 'ASC', 'Kaltstartstrom' => 'ASC'], 4, $i * 4);
                else $batteries = $entityManager->getRepository(BatterieRegal::class)->findBy(['Regal' => 'Regal ' . $number, 'Hersteller' => $value['hersteller']], ['Kapazitaet' => 'ASC', 'Kaltstartstrom' => 'ASC'], 16, $i * 16);

                $content .= '<div class="col-md-6">
                <div class="brand-title"><img src="/images/hersteller/' . $value['hersteller'] . '.jpg" style="width: auto; height:120px; ';
                if ($number == 5 and $i !== 0) $content .= 'visibility:hidden';
                $content .= '" alt="' . $value['hersteller'] . '" />';
                if ($key === 1 or ($key === 0 and $i === 1)) $content .= '<span style="color: #fff">' . $number . '</span>';
                $content .= '</div>';
                $content .= '<table class="table table-dark">
                <thead><td>Artikel</td><td>Kapazit&auml;t (- Kaltstartstrom)</td><td>Abmessungen</td><td>Preis</td></thead>';
                foreach ($batteries as $battery) {
                    $content .= '<tr><td>' . $battery->getArtikelnummer() . '</td><td>' . $battery->getKapazitaet() . 'Ah - ' . $battery->getKaltstartstrom() . 'A/EN' . '</td><td>' . $battery->getMasse() . '</td><td>' . $battery->getPreis() . '&euro;<br />';
                }
                $content .= '</table></div>';
            }
        }
        switch ($number) {
            case 1:
                $content .= "</div><div class='battery-image'><img src='/images/batterien-bilder/batterie-regal-1.png' style='width: auto; height: 310px' alt='' /></div></div></body></html>";
                break;
            case 2:
                $content .= "</div><div class='battery-image'><img src='/images/batterien-bilder/batterie-regal-2.png' style='width: auto; height: 310px' alt='' /></div></div></body></html>";
                break;
            case 3:
                $content .= "</div><div class='battery-image'><img src='/images/batterien-bilder/batterie-regal-3.png' style='width: auto; height: 400px' alt='' /></div></div></body></html>";
                break;
            case 4:
                $content .= "</div><div class='battery-image'><img src='/images/batterien-bilder/batterie-regal-4.png' style='width: auto; height: 370px' alt='' /></div></div></body></html>";
                break;
            case 6:
                $content .= "</div><div class='battery-image'><img src='/images/batterien-bilder/batterie-regal-6.png' style='width: auto; height: 370px' alt='' /></div></div></body></html>";
                break;
            case 7:
                $content .= "</div><div class='battery-image'><img src='/images/batterien-bilder/batterie-regal-7.png' style='width: auto; height: 380px' alt='' /></div></div></body></html>";
                break;
            default:
                $content .= "</div></div></body></html>";
                break;
        }


        return new Response($content);
    }

}
