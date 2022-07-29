<?php

namespace App\Service\Mailer;

use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class Mailer
{
    public function __construct(
        private MailerInterface $mailer,
        private Environment     $twig
    )
    {
    }

    public function sendRegistrationEmail(User $user): void
    {
        $template = 'email/registration.html.twig';
        $subject  = $this->renderSubject($template);
        $rendered = $this->twig->render($template, [
            'user' => $user
        ]);

        $this->send($user->getEmail(), $subject, $rendered);
    }

    private function renderSubject(string $template, array $context = []): string
    {
        return $this->twig->load($template)->renderBlock('subject', $context);
    }

    private function send($receivers, string $subject, string $body): void
    {
        if (empty($receivers)) {
            return;
        }

        $email = (new Email())
            ->from(new Address($_ENV['MAILER_FROM_EMAIL'], $_ENV['MAILER_FROM_NAME']))
            ->to($receivers)
            ->subject($subject)
            ->html($body);

        $this->mailer->send($email);
    }
}
