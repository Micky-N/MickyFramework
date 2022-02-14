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
        $configPermission = config('permission');
        $method = $configPermission['strategy'] ?? null;
        $allow_default = $configPermission['allow_default'] ?? null;
        if(method_exists($this, $method)){
            return $this->{$method}($user, $permission, $subject, $allow_default);
        } else {
            return $this->affirmative($user, $permission, $subject);
        }
    }


    /**
     * This grants access as soon as there is one voter granting access
     *
     * @param $user
     * @param $permission
     * @param null $subject
     * @return bool
     * @throws Exception
     */
    public function affirmative($user, $permission, $subject = null): bool
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
     * This grants access if there are more voters granting access than denying
     * In case of a tie the decision is based on the allow_if_equal_granted_denied
     * config option (defaulting to true)
     *
     * @param $user
     * @param $permission
     * @param null $subject
     * @param bool $allow_if_equal_granted_denied
     * @return bool
     * @throws Exception
     */
    public function consensus($user, $permission, $subject = null, bool $allow_if_equal_granted_denied = true): bool
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
     * This only grants access if there is no voter denying access
     *
     * @param $user
     * @param $permission
     * @param null $subject
     * @return bool
     * @throws Exception
     */
    public function unanimous($user, $permission, $subject = null): bool
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
     * This grants or denies access by the first voter that does not abstain, based on their service priority
     *
     * @param $user
     * @param $permission
     * @param null $subject
     * @return bool
     * @throws Exception
     */
    public function priority($user, $permission, $subject = null): bool
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