<?php


namespace App\Http\Controllers\Admin;


use App\Models\Notifiable;
use MkyCore\Controller;

class NotificationController extends Controller
{
    public function subscribe($subscribe)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if(is_array($data) && isset($data['endpoint'])){
            if($subscribe === 'subscribe'){
                $second = (int)floor($data['expirationTime']) / 1000;
                $expirationTime = $second === 0 ? date('Y-m-d H:i:s') : (new \DateTime("@$second"))->format('Y-m-d H:i:s');
                $add = [
                    'notifiable_id' => auth()->id,
                    'endpoint' => $data['endpoint'],
                    'expirationTime' => $expirationTime,
                    'auth' => $data['keys']['auth'],
                    'p256dh' => $data['keys']['p256dh'],
                ];
                $already = !is_null(auth()->webPushUser);
                if($already){
                    unset($add['notifiable_id']);
                    $query = auth()->webPushUser->modify($add);
                    if($query !== false){
                        echo json_encode(['status' => 'ok', 'message' => 'update subscribed']);
                        die();
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Try again']);
                        die();
                    }
                } else {
                    $query = Notifiable::create($add);
                    if($query !== false){
                        echo json_encode(['status' => 'ok', 'message' => 'subscribed']);
                        die();
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Try again']);
                        die();
                    }
                }

            }
        } else {
            echo json_encode(['status' => 'ok', 'message' => 'already subscribed']);
            die();
        }
    }
}