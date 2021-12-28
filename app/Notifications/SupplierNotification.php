<?php


namespace App\Notifications;


use Core\Interfaces\NotificationInterface;
use Core\WebPushMessage;

class SupplierNotification implements NotificationInterface
{

    /**
     * @var mixed
     */
    private $process;

    public function __construct($process)
    {
        $this->process = $process;
    }

    public function via($notifiable)
    {
        return ['beams'];
    }

    public function toBeams($notifiable)
    {
        return [
            'notification' => [
                'title' => "Fournisseur Affiché",
                'body' => sprintf("Le fournisseur %s est affiché", $this->process->name),
                'icon' => "http://pngimg.com/uploads/php/php_PNG49.png",
                'image' => 'https://www.synolia.com/wp-content/uploads/2019/12/header-vue-js.jpg',
                'deep_link' => 'https://mickyframework.loc/'.route('suppliers.show', ['supplier' => $this->process->code_supplier]),
                'hide_notification_if_site_has_focus' => false,
            ]
        ];
    }
}