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
     * @return bool
     * @throws Exception
     */
    public function can(string $permission, $subject = null): bool
    {
        foreach ($this->voters as $voter){
            if($voter->canVote($permission, $subject)){
                $auth = new AuthManager();
                $auth->getAuth();
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
        return false;
    }

    public function addVoter(VoterInterface $voter): void
    {
        $this->voters[] = $voter;
    }
}