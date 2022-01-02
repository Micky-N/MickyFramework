<?php


namespace Core;


use Exception;
use Core\Facades\StandardDebugBar;
use Core\Interfaces\VoterInterface;

class Permission
{
    /**
     * @var VoterInterface[]
     */
    private array $voters = [];

    /**
     * Autorise l'accès si true
     *
     * @param string $permission
     * @param null $subject
     * @return bool|void
     * @throws Exception
     */
    public function can(string $permission, $subject = null)
    {
        if($this->test($permission, $subject) === false){
            return Controller::forbidden();
        }
        return true;
    }

    /**
     * Ajoute un voter
     *
     * @param VoterInterface $voter
     */
    public function addVoter(VoterInterface $voter): void
    {
        $this->voters[] = $voter;
    }

    /**
     * Test la permission
     *
     * @param string $permission
     * @param null $subject
     * @return bool
     * @throws Exception
     */
    public function test(string $permission, $subject = null): bool
    {
        foreach ($this->voters as $voter) {
            if($voter->canVote($permission, $subject)){
                $auth = new AuthManager();
                if($auth->isLoggin()){
                    $vote = $voter->vote($auth->getAuth(), $permission, $subject);
                    if(config('env') === 'local'){
                        $this->voterDebugBar($voter, $vote, $permission);
                    }
                    if($vote === true){
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Inscrit le voter dans le debugBar
     *
     * @param VoterInterface $voter
     * @param bool $vote
     * @param string $permission
     */
    private function voterDebugBar(VoterInterface $voter, bool $vote, string $permission){
        $className = get_class($voter);
        $type = $vote ? 'info' : 'error';
        $message = "$className : ".($vote ? "yes" : "no")." on $permission";
        StandardDebugBar::addMessage('Voters', $message, $type);
    }
}