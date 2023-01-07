<?php


namespace App\UIModule\Listeners;


use MkyCore\Interfaces\EventInterface;
use MkyCore\Interfaces\ListenerInterface;
use MkyCore\Mail\Mailer;

class SendResetPasswordLinkListener implements ListenerInterface
{

    /**
     * @inheritDoc
     */
    public function handle(EventInterface $event)
    {
        $url = $event->getParam('url') . '?' . http_build_query(['token' => $event->getParam('token')]);
        $mailer = new Mailer();
        $template = $mailer->useTemplate()
            ->greeting('Hello!')
            ->paragraph('You recently requested to reset your password. Click the button below to proceed.')
            ->button(['Reset password' => $url], 'green')
            ->paragraph('If you did not request a password reset, please ignore this email. This password reset link is only valid for the next 30 minutes.')
            ->paragraph('If you have any comments or questions don’t hesitate to reach us at ndingamickael@gmail.com')
            ->footer('If you cannot reach the link, copy and paste the link below into your browser', [$url => $url])
            ->signature(env('APP_NAME'));

        $mailer->buildMessage('Forgot password')
            ->setFrom(['john@doe.com' => 'John Doe'])
            ->setTo(['receiver@domain.org', $event->getTarget()->email()])
            ->setTemplate($template, true);

        $mailer->send();
    }
}