<?php


namespace App\UIModule\Listeners;


use App\UIModule\UIKernel;
use MkyCore\Abstracts\Entity;
use MkyCore\Database;
use MkyCore\Exceptions\Container\FailedToResolveContainerException;
use MkyCore\Exceptions\Container\NotInstantiableContainerException;
use MkyCore\Interfaces\EventInterface;
use MkyCore\Interfaces\ListenerInterface;
use MkyCore\PasswordReset\PasswordResetManager;
use ReflectionException;

class SaveResetPasswordTokenListener implements ListenerInterface
{

    /**
     * @inheritDoc
     * @param EventInterface $event
     * @throws FailedToResolveContainerException
     * @throws NotInstantiableContainerException
     * @throws ReflectionException
     */
    public function handle(EventInterface $event)
    {
        /**
         * @var PasswordResetManager $passwordResetManager
         * @var Entity $entity
         */
        $entity = $event->getTarget();
        $passwordResetManager = app()->get(UIKernel::PASSWORD_RESET_MANAGER);
        $passwordReset = $passwordResetManager->getEntity([
            'entity' => Database::stringifyEntity($entity),
            'entity_id' => $entity->{$entity->getPrimaryKey()}(),
            'token' => $event->getParam('token'),
            'expiresAt' => now()->addMinutes($event->getParam('expiresAt'))->format('Y-m-d H:i:s')
        ]);
        $passwordResetManager->save($passwordReset);
    }
}