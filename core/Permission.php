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
     * Authorize if true
     *
     * @param string $permission
     * @param null $subject
     * @return bool|void
     * @throws Exception
     */
    public function can(string $permission, $subject = null)
    {
        if($this->authorizeAuth($permission, $subject) === false){
            return ErrorController::forbidden();
        }
        return true;
    }

    /**
     * Authorize permission for Auth
     *
     * @param string $permission
     * @param null $subject
     * @return bool
     * @throws Exception
     */
    public function authorizeAuth(string $permission, $subject = null): bool
    {
        $auth = new AuthManager();
        if($auth->isLogin()){
            return $this->authorize($auth->getAuth(), $permission, $subject);
        }
        return false;
    }

    /**
     * Authorize permission
     *
     * @param mixed $user
     * @param string $permission
     * @param null $subject
     * @return bool
     * @throws Exception
     */
    public function authorize($user, string $permission, $subject = null): bool
    {
        foreach ($this->voters as $voter) {
            if($voter->canVote($permission, $subject)){
                $vote = $voter->vote($user, $permission, $subject);
                if(config('env') === 'local'){
                    $this->voterDebugBar($voter, $vote, $permission);
                }
                if($vote === true){
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Add voter
     *
     * @param VoterInterface $voter
     */
    public function addVoter(VoterInterface $voter): void
    {
        $this->voters[] = $voter;
    }

    /**
     * Add authorized voter to debugBar
     *
     * @param VoterInterface $voter
     * @param bool $vote
     * @param string $permission
     */
    private function voterDebugBar(VoterInterface $voter, bool $vote, string $permission)
    {
        $className = get_class($voter);
        $type = $vote ? 'info' : 'error';
        $message = "$className : " . ($vote ? "yes" : "no") . " on $permission";
        StandardDebugBar::addMessage('Voters', $message, $type);
    }
}