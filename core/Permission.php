<?php


namespace Core;


use App\Models\User;
use Core\Interfaces\VoterInterface;
use Exception;

class Permission
{
    /**
     * @var VoterInterface[]
     */
    private array $voters = [];

    /**
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

    public function addVoter(VoterInterface $voter): void
    {
        $this->voters[] = $voter;
    }

    /**
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
                if($auth->getAuth()){
                    $vote = $voter->vote($auth->getAuth(), $permission, $subject);
                    try {
                        if(config('env') === 'local'){
                            ConsoleDebugger::permissionDebug($voter, $vote, $permission, $auth->getAuth(), $subject);
                        }
                    } catch (Exception $e) {
                        throw new Exception($e->getMessage());
                    }
                    if($vote === true){
                        return true;
                    }
                }
            }
        }
        return false;
    }
}