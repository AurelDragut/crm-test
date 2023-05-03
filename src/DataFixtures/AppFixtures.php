<?php

namespace App\DataFixtures;

use App\Factory\FrageFactory;
use App\Factory\GrundFactory;
use App\Factory\HaendlerFactory;
use App\Factory\MenuePunktFactory;
use App\Factory\MenuFactory;
use App\Factory\SeiteFactory;
use App\Factory\SlideFactory;
use App\Factory\StatusFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Factory\BenutzerFactory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Load Users
        BenutzerFactory::new()
            ->withAttributes([
                'email' => 'reklamation@winnerbatterien.de',
                'password' => '$2y$13$5WtdW5khJizaXgmYEDF1He259xuALBGyX.l9jmoJvXkZmbljcsHVy',
                'roles' => ['ROLE_ADMIN'],
                'name' => 'Angela'

            ])
            ->create();
        BenutzerFactory::new()
            ->withAttributes([
                'email' => 'info@winnerbatterien.de',
                'password' => '$2y$13$5WtdW5khJizaXgmYEDF1He259xuALBGyX.l9jmoJvXkZmbljcsHVy',
                'roles' => ['ROLE_MITARBEITER'],
                'name' => 'Info'

            ])
            ->create();
        BenutzerFactory::new()
            ->withAttributes([
                'email' => 'service@winnerbatterien.de',
                'password' => '$2y$13$5WtdW5khJizaXgmYEDF1He259xuALBGyX.l9jmoJvXkZmbljcsHVy',
                'roles' => ['ROLE_MITARBEITER'],
                'name' => 'Service'
            ])
            ->create();
        BenutzerFactory::new()
            ->withAttributes([
                'email' => 'verkauf@winnerbatterien.de',
                'password' => '$2y$13$5WtdW5khJizaXgmYEDF1He259xuALBGyX.l9jmoJvXkZmbljcsHVy',
                'roles' => ['ROLE_MITARBEITER'],
                'name' => 'Verkauf'

            ])
            ->create();
        BenutzerFactory::new()
            ->withAttributes([
                'email' => 'kundenservice@winnerbatterien.de',
                'password' => '$2y$13$5WtdW5khJizaXgmYEDF1He259xuALBGyX.l9jmoJvXkZmbljcsHVy',
                'roles' => ['ROLE_MITARBEITER'],
                'name' => 'Kunden Service'

            ])
            ->create();
        BenutzerFactory::new()
            ->withAttributes([
                'email' => 'mail@winnerbatterien.de',
                'password' => '$2y$13$5WtdW5khJizaXgmYEDF1He259xuALBGyX.l9jmoJvXkZmbljcsHVy',
                'roles' => ['ROLE_MITARBEITER'],
                'name' => 'Mail'

            ])
            ->create();
        BenutzerFactory::new()
            ->withAttributes([
                'email' => 'support@winnerbatterien.de',
                'password' => '$2y$13$5WtdW5khJizaXgmYEDF1He259xuALBGyX.l9jmoJvXkZmbljcsHVy',
                'roles' => ['ROLE_MITARBEITER'],
                'name' => 'Support'

            ])
            ->create();

        FrageFactory::new()
            ->withAttributes([
                'frage' => 'Wie lange dauert die Bearbeitung nach einer Auftragserstellung?',
                'antwort' => '<div>&nbsp;In der Regel erhalten Sie innerhalb von 48 Stunden eine erste Rückmeldung.&nbsp;</div>'
            ])
            ->create();

        FrageFactory::new()
            ->withAttributes([
                'frage' => 'Warum sollte ich easyRMA benutzen?',
                'antwort' => '<div>Über das einfache und unkomplizierte System können Retouren und Rücksendungen ganz einfach abgewickelt werden. Der Vorteil für Sie als Kunde besteht darin, dass Sie jederzeit über den Status Ihrer RMA informiert werden. Somit brauchen Sie sich um nichts zu kümmern. Wir haben alle nötigen Informationen direkt zur Hand und können Ihre Reklamation bzw. Beschwerde so noch schneller bearbeiten.&nbsp;</div>'
            ])
            ->create();
        FrageFactory::new()
            ->withAttributes([
                'frage' => 'Kann ich Fotos oder Dateien anhängen, die für die Reklamation wichtig sind?',
                'antwort' => '<div>&nbsp;Der Upload von Dateien ist jederzeit mit allen Geräten mit einem Browser möglich.</div>'
            ])
            ->create();
        FrageFactory::new()
            ->withAttributes([
                'frage' => 'Welche Versanddienstleister werden unterstützt?',
                'antwort' => '<div>&nbsp;Wir bitten Sie Ihre Rücksendung sicher und fachgerecht mit einem Versandpartner Ihrer Wahl vorzunehmen.&nbsp;</div>'
            ])
            ->create();
        FrageFactory::new()
            ->withAttributes([
                'frage' => 'Wann erhalte ich mein Geld zurück?',
                'antwort' => '<div>&nbsp;Die Rückerstattung erfolgt nach ca. 4-5 Werktagen nach Erhalt der Ware.&nbsp;</div>'
            ])
            ->create();
        FrageFactory::new()
            ->withAttributes([
                'frage' => 'Wer übernimmt die Rücksendekosten?',
                'antwort' => '<div><strong>Widerruf:</strong> Trägt der Käufer die Rücksendekosten<br> <strong>Reklamation:</strong> Innerhalb der 6 Monatsfrist trägt der Verkäufer die Rücksendekosten gegen Vorlage der Versandquittung&nbsp;</div>'
            ])
            ->create();
        FrageFactory::new()
            ->withAttributes([
                'frage' => 'Wo kann eine Batterie (Gefahrgut) fachgerecht entsorgt werden?',
                'antwort' => '<div>&nbsp;An einem Wertstoff- oder Recyclinghof&nbsp;</div>'
            ])
            ->create();

        GrundFactory::new()
            ->withAttributes([
                'inhalt' => 'Bestellte Ware ist nicht eingetroffen.',
                'ziel_email' => 'reklamation@siga-batterien.de',
                'titel' => 'Bestellte Ware ist nicht eingetroffen.'
            ])
            ->create();
        GrundFactory::new()
            ->withAttributes([
                'inhalt' => 'Es wurde Waren geliefert, die nicht bestellt wurden',
                'ziel_email' => 'retouren@siga-batterien.de',
                'titel' => 'Es wurde Waren geliefert, die nicht bestellt wurden.'
            ])
            ->create();
        GrundFactory::new()
            ->withAttributes([
                'inhalt' => 'Die Lieferung ist unvollständig',
                'ziel_email' => 'reklamation@siga-batterien.de',
                'titel' => 'Die Lieferung ist unvollständig.'
            ])
            ->create();
        GrundFactory::new()
            ->withAttributes([
                'inhalt' => 'Bestellte Ware ist beschädigt (durch Transportschaden und Versand)',
                'ziel_email' => 'reklamation@siga-batterien.de',
                'titel' => 'Bestellte Ware ist beschädigt (durch Transportschaden und Versand)'
            ])
            ->create();
        GrundFactory::new()
            ->withAttributes([
                'inhalt' => 'Fehlkauf Ware',
                'ziel_email' => 'retouren@siga-batterien.de',
                'titel' => 'Fehlkauf Ware'
            ])
            ->create();
        GrundFactory::new()
            ->withAttributes([
                'inhalt' => 'Rückgabe ohne Angaben von Gründen innerhalb eines Zeitraums',
                'ziel_email' => 'retouren@siga-batterien.de',
                'titel' => 'Rückgabe ohne Angaben von Gründen innerhalb eines Zeitraums'
            ])
            ->create();
        GrundFactory::new()
            ->withAttributes([
                'inhalt' => 'Länger zurückliegende Reklamation',
                'ziel_email' => 'reklamation@siga-batterien.de',
                'titel' => 'Länger zurückliegende Reklamation'
            ])
            ->create();
        GrundFactory::new()
            ->withAttributes([
                'inhalt' => 'Umtausch Optionen',
                'ziel_email' => 'retouren@siga-batterien.de',
                'titel' => 'Umtausch Optionen'
            ])
            ->create();

        HaendlerFactory::new()
            ->withAttributes([
                'name' => 'Batterie Spezialist',
                'url' => 'https://www.batteriespezialist.de/',
                'bild' => 'Battspez.png'
            ])
            ->create();
        HaendlerFactory::new()
            ->withAttributes([
                'name' => 'Autobatterien24',
                'url' => 'https://www.Autobatterien24.com/',
                'bild' => 'Auto24.png'
            ])
            ->create();

        HaendlerFactory::new()
            ->withAttributes([
                'name' => 'WinnerBatterien',
                'url' => 'https://www.WinnerBatterien.de/',
                'bild' => 'winner.png'
            ])
            ->create();

        HaendlerFactory::new()
            ->withAttributes([
                'name' => 'Versorgungsbatterie',
                'url' => 'https://www.Versorgungsbatterie.de/',
                'bild' => 'versorgung.png'
            ])
            ->create();

        HaendlerFactory::new()
            ->withAttributes([
                'name' => 'Solarbatterie',
                'url' => 'https://www.solarbatterie.com/',
                'bild' => 'solar.png'
            ])
            ->create();
        HaendlerFactory::new()
            ->withAttributes([
                'name' => 'BSA Batterien',
                'url' => 'https://www.bsabatterien.de/',
                'bild' => 'BSA.png'
            ])
            ->create();
        HaendlerFactory::new()
            ->withAttributes([
                'name' => 'Langzeit Batterien',
                'url' => 'https://www.langzeitbatterien.de/',
                'bild' => 'langzeit.png'
            ])
            ->create();
        HaendlerFactory::new()
            ->withAttributes([
                'name' => 'Loadchamp',
                'url' => 'https://www.loadchamp.de/',
                'bild' => 'Loadchamp.png'
            ])
            ->create();
        HaendlerFactory::new()
            ->withAttributes([
                'name' => 'NRG',
                'url' => NULL,
                'bild' => 'NRG.png'
            ])
            ->create();
        HaendlerFactory::new()
            ->withAttributes([
                'name' => 'Solis',
                'url' => NULL,
                'bild' => 'Solis.png'
            ])
            ->create();
        HaendlerFactory::new()
            ->withAttributes([
                'name' => 'Tokohama',
                'url' => NULL,
                'bild' => 'Tokohama.png'
            ])
            ->create();
        HaendlerFactory::new()
            ->withAttributes([
                'name' => 'SIGA Batteries',
                'url' => 'https://www.siga-batterien.de/',
                'bild' => 'SIGA.png'
            ])
            ->create();
        HaendlerFactory::new()
            ->withAttributes([
                'name' => 'Winter Batteriem',
                'url' => NULL,
                'bild' => 'Winter.png'
            ])
            ->create();
        HaendlerFactory::new()
            ->withAttributes([
                'name' => 'MBS',
                'url' => NULL,
                'bild' => 'MBS.png'
            ])
            ->create();

        MenuFactory::new()
            ->withAttributes([
                'titel' => 'Menu #1',
                'position' => 1
            ])
            ->create();
        MenuFactory::new()
            ->withAttributes([
                'titel' => 'Menu #2',
                'position' => 2
            ])
            ->create();
        MenuFactory::new()
            ->withAttributes([
                'titel' => 'Menu #3',
                'position' => 3
            ])
            ->create();

        SeiteFactory::new()
            ->withAttributes([
                'titel' => 'Impressum',
                'meta_beschreibung' => "Rechnungsaddrese\r\n\r\nBatterie-Reklamation.de\r\nEin Unternehmen der SIGA Batteries GmbH\r\nGeschäftsführer: Chris Kiriazis, Johannes Papasis",
                'inhalt' => "<p><strong>Rechnungsaddrese</strong></p>\r\n\r\n<p>Batterie-Reklamation.de<br />\r\nEin Unternehmen der SIGA Batteries GmbH<br />\r\nGesch&auml;ftsf&uuml;hrer: Chris Kiriazis, Johannes Papasis<br />\r\n<br />\r\nCasterfeldstr. 66-72<br />\r\n68199 Mannheim<br />\r\nDeutschland<br />\r\n<br />\r\nE-Mail: reklamation(@)siga-batterien(.)de<br />\r\n<br />\r\nAmtsgericht Mannheim<br />\r\nUSt-IdNr.: DE815694168<br />\r\nSteuerNr: 38188/24094<br />\r\n<br />\r\nRegistereintragungen:<br />\r\nRegistergericht: Amtsgericht Mannheim HRB 727294<br />\r\n<br />\r\n<strong>Lieferanschrift</strong><br />\r\nSIGA Batteries GmbH<br />\r\nCasterfeldstra&szlig;e 66-72<br />\r\n681699 Mannheim<br />\r\nDeutschland<br />\r\n<br />\r\nHaftungshinweis<br />\r\nDie Firma Batterie-Reklamation.de - Ein Unternehmen der SIGA Batteries GmbH &uuml;bernimmt keinerlei Gew&auml;hr f&uuml;r die Aktualit&auml;t, Korrektheit, Vollst&auml;ndigkeit oder Qualit&auml;t der bereitgestellten Informationen. Haftungsanspr&uuml;che die durch die Nutzung oder Nichtnutzung der dargebotenen Informationen bzw. durch die Nutzung fehlerhafter und unvollst&auml;ndiger Informationen verursacht worden sind, sind grunds&auml;tzlich ausgeschlossen, sofern seitens der Batterie-Reklamation.de - Ein Unternehmen der SIGA Batteries GmbH kein nachweislich vors&auml;tzliches oder grob fahrl&auml;ssiges Verschulden vorliegt. Wir &uuml;bernehmen wir keine Haftung f&uuml;r die Inhalte externer Links.<br />\r\n<br />\r\nVerantwortlich f&uuml;r den Inhalt gem&auml;&szlig; &sect;10 Abs. 3 MDStV.:<br />\r\nBatterie-Reklamation.de - Ein Unternhemen der SIGA Batteries GmbH, Casterfeldstr. 66-72, 68199 Mannheim<br />\r\n<br />\r\nVerantwortlicher i.S.v. &sect; 55 Abs. 2 RStV:<br />\r\nJohannes Papasis, Chris Kiriazis<br />\r\n<br />\r\n-Produkt- und Firmennamen sowie Logos sind Marken der jeweiligen Eigent&uuml;mer und werden auf diesen Seiten ausschliesslich zu Informationszwecken eingesetzt.<br />\r\n-Bildmaterial: Fotolia.com<br />\r\n<br />\r\nDie Europ&auml;ische Kommission stellt eine Plattform zur Online-Streitbeilegung (OS) bereit, die Sie unter https://ec.europa.eu/consumers/odr/ finden.</p>",
                'schnecke' => 'impressum'
            ])
            ->create();
        SeiteFactory::new()
            ->withAttributes([
                'titel' => 'Startseite',
                'meta_beschreibung' => "Retouren- / Widerruf- und Reklamationsabwicklung\r\nSie haben die falsche Batterie erhalten von\r\nSIGA, Winner, WINTER, NRG, SOLIS, BSA, Langzeit, Loadchamp, Banner, Tokohama, SHELL",
                'inhalt' => "<div class=\"row\">\r\n<div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\">\r\n<h1>Retouren- / Widerruf- / Garantie- und Reklamationsabwicklung</h1>\r\n\r\n<h3>SIGA, Winner, WINTER, NRG, SOLIS, BSA, Langzeit, Loadchamp, Banner, Tokohama, SHELL</h3>\r\n\r\n<p>Sie haben ein Anliegen der folgenden Hersteller: SIGA, Winner, WINTER, NRG, SOLIS, BSA, Langzeit, Loadchamp, Tokohama dann sind Sie bei uns richtig.</p>\r\n\r\n<p>&Uuml;ber das einfache und unkomplizierte System k&ouml;nnen Retouren und R&uuml;cksendungen ganz einfach abgewickelt werden. Der Vorteil f&uuml;r Sie als Kunde besteht darin, dass Sie jederzeit &uuml;ber den Status Ihrer Batterie informiert werden. Somit brauchen Sie sich um nichts zu k&uuml;mmern. Wir haben alle n&ouml;tigen Informationen direkt zur Hand und k&ouml;nnen Ihre Reklamation bzw. Beschwerde so noch schneller bearbeiten.</p>\r\n</div>\r\n</div>",
                'schnecke' => 'startseite'
            ])
            ->create();

        SlideFactory::new()
            ->withAttributes([
                'bild' => 'slide-show1.jpg',
                'text' => "<h2>Retouren- / Widerruf- und Reklamationsabwicklung</h2>\r\n\r\n<h3>Sie wollen ihr Batterie reklamieren?</h3>\r\n\r\n<h4>SIGA, Winner, WINTER, NRG, SOLIS, BSA, Langzeit, Loadchamp, Banner, Tokohama, SHELL</h4>",
                'reihenfolge' => 1
            ])
            ->create();
        SlideFactory::new()
            ->withAttributes([
                'bild' => 'slide-show2.jpg',
                'text' => "<h2>Retouren- /  Widerruf- und Reklamationsabwicklung</h2>\r\n                        <h3>Sie wollen ihr Batterie reklamieren?</h3>\r\n                        <h4>SIGA, Winner, WINTER, NRG, SOLIS, BSA, Langzeit, Loadchamp, Banner, Tokohama, SHELL</h4>",
                'reihenfolge' => 2
            ])
            ->create();
        SlideFactory::new()
            ->withAttributes([
                'bild' => 'slide-show3.jpg',
                'text' => '<h2>Retouren- /  Widerruf- und Reklamationsabwicklung</h2>\r\n                        <h3>Sie wollen ihr Batterie reklamieren?</h3>\r\n                        <h4>SIGA, Winner, WINTER, NRG, SOLIS, BSA, Langzeit, Loadchamp, Banner, Tokohama, SHELL</h4>',
                'reihenfolge' => 3
            ])
            ->create();

        StatusFactory::new()
            ->withAttributes([
                'Bezeichnung' => 'Auftrag erfasst',
                'Farbe' => '#0051ff',
                ])
            ->create();
        StatusFactory::new()
            ->withAttributes([
                'Bezeichnung' => 'Auftrag in Bearbeitung',
                'Farbe' => '#ff4400',
            ])
            ->create();
        StatusFactory::new()
            ->withAttributes([
                'Bezeichnung' => 'Retourenschein an Kunden gesendet',
                'Farbe' => '#ebd807',
            ])
            ->create();
        StatusFactory::new()
            ->withAttributes([
                'Bezeichnung' => 'Im Lager eingetroffen',
                'Farbe' => '#93c900',
            ])
            ->create();
        StatusFactory::new()
            ->withAttributes([
                'Bezeichnung' => 'Prüfung abgeschlossen - Mitarbeiter meldet sich in Kürze bei Ihnen',
                'Farbe' => '#99ff00',
            ])
            ->create();
        StatusFactory::new()
            ->withAttributes([
                'Bezeichnung' => 'Rücksendung an Kunden',
                'Farbe' => '#afe077',
            ])
            ->create();
        StatusFactory::new()
            ->withAttributes([
                'Bezeichnung' => 'Auftrag abgeschlossen',
                'Farbe' => '#52cc00',
            ])
            ->create();
        StatusFactory::new()
            ->withAttributes([
                'Bezeichnung' => 'Neue E-Mail',
                'Farbe' => '#ff4400',
            ])
            ->create();
        StatusFactory::new()
            ->withAttributes([
                'Bezeichnung' => 'Order registered',
                'Farbe' => '#ff0000',
            ])
            ->create();
        StatusFactory::new()
            ->withAttributes([
                'Bezeichnung' => 'Gutschrift erstellt - Erstattung erfolgt in Kürze',
                'Farbe' => '#bd6bbd',
            ])
            ->create();
        StatusFactory::new()
            ->withAttributes([
                'Bezeichnung' => 'Rücksendung Kunde wird erwartet',
                'Farbe' => '#ffffff',
            ])
            ->create();
        StatusFactory::new()
            ->withAttributes([
                'Bezeichnung' => 'in Prüfung',
                'Farbe' => '#85505a',
            ])
            ->create();
        StatusFactory::new()
            ->withAttributes([
                'Bezeichnung' => 'Neusendung veranlasst ',
                'Farbe' => '#ede8d5',
            ])
            ->create();
        StatusFactory::new()
            ->withAttributes([
                'Bezeichnung' => 'Austausch eingeleitet',
                'Farbe' => '#badbca',
            ])
            ->create();
        StatusFactory::new()
            ->withAttributes([
                'Bezeichnung' => 'Versandkostenerstattung an Buchhaltung weitergeleitet - Erstattung erfolgt in Kürze',
                'Farbe' => '#009952',
            ])
            ->create();
        StatusFactory::new()
            ->withAttributes([
                'Bezeichnung' => 'Abholung via Spedi veranlasst - Terminabsprache folgt in Kürze',
                'Farbe' => '#d6d6d6',
            ])
            ->create();
        StatusFactory::new()
            ->withAttributes([
                'Bezeichnung' => 'Neue Mail - Batterie defekt - Frist Entsorgung',
                'Farbe' => '#753535',
            ])
            ->create();
        StatusFactory::new()
            ->withAttributes([
                'Bezeichnung' => 'Wiedervorlage',
                'Farbe' => '#7b8a85',
            ])
            ->create();
        StatusFactory::new()
            ->withAttributes([
                'Bezeichnung' => 'Zustellfehler - Retoure wird erwartet',
                'Farbe' => '#8a8279',
            ])
            ->create();

        $manager->flush();
    }
}
