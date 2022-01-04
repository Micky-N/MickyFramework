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
    private string $action;

    public function __construct($process, string $action = '')
    {
        $this->process = $process;
        $this->action = $action;
    }

    public function via($notifiable)
    {
        return ['notification'];
    }

    public function toWebPush($notifiable)
    {
        switch ($this->action):
            case 'update':
                return WebPushMessage::create()->icon('http://pngimg.com/uploads/php/php_PNG49.png')
                    ->image('https://www.synolia.com/wp-content/uploads/2019/12/header-vue-js.jpg')
                    ->link(route('categories.show', ['category' => $this->process->code_category]))
                    ->title('Catégorie Modifiée')
                    ->body(sprintf('La categorie %s a été modifié', $this->process->name))
                    ->actions([
                        ['action' => 'open', 'title' => 'Ouvrir'],
                        ['action' => 'markAsRead', 'title' => 'Marquer comme lu']
                    ])->toArray();
                break;
            case 'create':
                return WebPushMessage::create()->icon('http://pngimg.com/uploads/php/php_PNG49.png')
                    ->image('https://www.synolia.com/wp-content/uploads/2019/12/header-vue-js.jpg')
                    ->link(route('categories.show', ['category' => $this->process->code_category]))
                    ->title('Catégorie Créée')
                    ->body(sprintf('La categorie %s a été ajouté', $this->process->name))
                    ->actions([
                        ['action' => 'open', 'title' => 'Ouvrir'],
                        ['action' => 'markAsRead', 'title' => 'Marquer comme lu']
                    ])->toArray();
        endswitch;
    }
}