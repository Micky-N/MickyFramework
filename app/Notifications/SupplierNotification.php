<?php


namespace App\Notifications;


use MkyCore\Interfaces\NotificationInterface;
use MkyCore\WebPushMessage;

class SupplierNotification implements NotificationInterface
{

    /**
     * @var mixed
     */
    private $process;
    private string $action;

    public function __construct($process, string $action = '')
    {
        $this->process = $process;
        $this->action = $action;
    }

    public function via($notifiable)
    {
        return ['webPush'];
    }

    public function toWebPush($notifiable)
    {
        switch ($this->action):
            default:
                return WebPushMessage::create()
                    ->icon('http://pngimg.com/uploads/php/php_PNG49.png')
                    ->image('https://www.synolia.com/wp-content/uploads/2019/12/header-vue-js.jpg')
                    ->link(route('suppliers.show', ['supplier' => $this->process->code_supplier]))
                    ->title('Fournisseur Affiché')
                    ->body(sprintf("Le fournisseur %s est affiché", $this->process->name))
                    ->actions([
                        ['action' => 'open', 'title' => 'Ouvrir'],
                        ['action' => 'markAsRead', 'title' => 'Marquer comme lu']
                    ])->toArray();
                break;
        endswitch;
    }
}