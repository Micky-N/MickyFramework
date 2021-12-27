<?php


namespace App\Notifications;


use Core\Interfaces\NotificationInterface;
use Core\WebPushMessage;

class CategoryNotification implements NotificationInterface
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
        return ['notification'];
    }

    public function toNotification($notifiable)
    {
        return WebPushMessage::create()->title('Catégorie Modifiée')
            ->icon('http://pngimg.com/uploads/php/php_PNG49.png')
            ->body(sprintf('La categorie %s modifié à été modifié', $this->process->name))
            ->image('https://www.synolia.com/wp-content/uploads/2019/12/header-vue-js.jpg')
            ->link(route('categories.show', ['category' => $this->process->code_category]))
            ->actions([
                ['action' => 'open', 'title' => 'Ouvrir'],
                ['action' => 'markAsRead', 'title' => 'Marquer comme lu']
            ])->toArray();
    }
}