<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/email')]
    public static function sendEmail(MailerInterface $mailer, $receiver, $subject, $message): void
    {
        $email = (new Email())
            ->from(new Address('mail@batterie-reklamation.de', 'Batterie Reklamation'))
            ->to($receiver)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)

            // get the image contents from a PHP resource
            // ->embed(fopen('/path/to/images/logo.png', 'r'), 'logo')

            // get the image contents from an existing file
            // ->embedFromPath('/path/to/images/signature.gif', 'footer-signature')

            // reference images using the syntax 'cid:' + "image embed name"
            //->html('<img src="cid:logo"> ... <img src="cid:footer-signature"> ...')

            // use the same syntax for images included as HTML background images
            //->html('... <div background="cid:footer-signature"> ... </div> ...')

            ->subject($subject)
            ->text($message)
            ->html($message);

        $mailer->send($email);
    }
}