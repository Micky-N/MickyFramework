<?php

namespace App\Http\Controllers;

use Core\Controller;
use Core\Facades\View;
use Pusher\Pusher;


class ChatMessageController extends Controller
{
    public function index()
    {
        return View::render('chat');
    }

    public function addMessage()
    {
        $message = json_decode(file_get_contents('php://input'), true);
        $pusher = new Pusher(_env('PUSHER_KEY'), _env('PUSHER_SECRET'), _env('PUSHER_ID'), array('cluster' => _env('PUSHER_CLUSTER')));
        $pusher->trigger("mky", 'my-event', $message);
        echo json_encode($message);die;
    }
}