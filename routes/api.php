<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return '{"status":"Congratulations! You have discovered a nothing!"}';
    return $request->user();
});


//api/vk/message
//api/vk/event

Route::get('/test', function () {
    return '{"status":"Congratulations! You have discovered a nothing!"}';
    //{"type":"message_new","object":{"date":1546724691,"from_id":14363074,"id":2,"out":0,"peer_id":14363074,"text":"КУУУУ","conversation_message_id":2,"fwd_messages":[],"important":false,"random_id":0,"attachments":[],"is_hidden":false},"group_id":176146542}
    //{"type":"message_new","object":{"date":1546724571,"from_id":14363074,"id":0,"out":0,"peer_id":2000000001,"text":"[club176146542|Роланд Оплотов] КУУУ","conversation_message_id":9,"fwd_messages":[],"important":false,"random_id":0,"attachments":[],"is_hidden":false},"group_id":176146542}


    //$method = 'messages.getConversations';
    //$method = 'messages.getByConversationMessageId';
    $method = 'messages.send';

    //$method = 'messages.getConversationMembers';
    $message = ($_SERVER['HTTP_HOST'] . '_time:' . time() . '_message:KABOOM!');
    $parameters = 'peer_id=2000000003&message=' . $message . '&random_id=1232132132';
    //$parameters = 'peer_id=2000000045&message=BOOM!';.
    $r = file_get_contents('https://api.vk.com/method/' . $method . '?' . $parameters . '&access_token=' . env("VK_API_GROUP_TOKEN") . '&v=5.92');

    dump($r);
    die;
});

Route::get('/test/group', function () {
    return '{"status":"Congratulations! You have discovered a nothing!"}';
    //https://oauth.vk.com/authorize?client_id=6816973&display=page&redirect_uri=https://oauth.vk.com/blank.html&scope=friends&response_type=token&v=5.52&scope=messages,notify,groups,offline,friends,email
    //{"type":"message_new","object":{"date":1546724691,"from_id":14363074,"id":2,"out":0,"peer_id":14363074,"text":"КУУУУ","conversation_message_id":2,"fwd_messages":[],"important":false,"random_id":0,"attachments":[],"is_hidden":false},"group_id":176146542}
    //{"type":"message_new","object":{"date":1546724571,"from_id":14363074,"id":0,"out":0,"peer_id":2000000001,"text":"[club176146542|Роланд Оплотов] КУУУ","conversation_message_id":9,"fwd_messages":[],"important":false,"random_id":0,"attachments":[],"is_hidden":false},"group_id":176146542}

    $token = env('VK_API_USER_TOKEN');
    //$method = 'messages.getConversations';
    //$method = 'messages.getByConversationMessageId';

    $method = 'messages.getConversationMembers';
    $message = ($_SERVER['HTTP_HOST'] . '_time:' . time() . '_message:KABOOM!');
    $parameters = 'peer_id=2000000008&random_id=' . rand(10000, 65536);
    $r = file_get_contents('https://api.vk.com/method/' . $method . '?' . $parameters . '&access_token=' . $token . '&v=5.92');

    dump($r);
    die;
});


Route::get('/test/debug', function (Request $request) {
    return '{"status":"Congratulations! You have discovered a nothing!"}';
    return App::call('App\Http\Controllers\Bot@generateEventAction'
        , ['peerId' => 2000000003]
    );

    return App::call('App\Http\Controllers\Bot@testAction', ['peerId' => 2000000005]);
    return App::call('App\Http\Controllers\Bot@sendMessageAction', ['peerId' => 2000000004]);
    return App::call('App\Http\Controllers\Bot@registerChatForCompetitions', ['peerId' => 2000000004]);
    return App::call('App\Http\Controllers\Bot@generateEventAction', ['peerId' => 2000000004]);
    die;

    $string = '[club176146542|] eventStart';
    $string = '[club176146542|Роланд Оплотов]';
    $string = '[club176146542|Роланд Оплотов] eventStart';
    $matches = [];
    $reg = '/^\[' . env("VK_GROUP_ID") . '\|(.*)\] +(\S+)[ ]{0,}(.*)?/';
    $r = preg_match($reg, $string, $matches);
    dump($reg);
    dump($r);
    dump($matches);
    die;
    \Illuminate\Support\Facades\Log::debug('myArray', ['asd']);
    return '{"status":"OK"}';
});


Route::post('/vk/callback', function (Request $requestVk) {
    return '{"status":"Congratulations! You have discovered a nothing!"}';
    try {


        $requestArray = $requestVk->all();

        $matches = [];
        //регулярка, ключ, пробел, команда, потом может быть пробел или несколько, потом любые символы
        $reg = '/^\[' . env("VK_GROUP_ID") . '\|(.*)\] +(\S+)[ ]{0,}(.*)?/';
        $command = '';
        //проверяем явки пароли, наличие сообщения и от кого оно
        //забираем регуляркой комманду


//        Illuminate\Support\Facades\Log::debug($requestVk->all());
//        Illuminate\Support\Facades\Log::debug(env("VK_API_GROUP_SECRET"));
//        Illuminate\Support\Facades\Log::debug($requestVk->get('secret') == env("VK_API_GROUP_SECRET"));
//        Illuminate\Support\Facades\Log::debug(isset($requestArray['object']['text']));
//        Illuminate\Support\Facades\Log::debug(isset($requestArray['object']['peer_id']));
//        Illuminate\Support\Facades\Log::debug(isset($requestArray['object']['peer_id']));
//        Illuminate\Support\Facades\Log::debug($requestArray['object']['peer_id']);
//        Illuminate\Support\Facades\Log::debug(env("VK_GROUP_ADMIN_ID"));
//        Illuminate\Support\Facades\Log::debug($requestArray['object']['from_id']);
//        Illuminate\Support\Facades\Log::debug($requestArray['object']['text']);
//        Illuminate\Support\Facades\Log::debug($reg);
//        Illuminate\Support\Facades\Log::debug(preg_match($reg, $requestArray['object']['text'], $matches));
//        Illuminate\Support\Facades\Log::debug($matches);

        if ($requestVk->get('type') == 'message_new' &&
            $requestVk->get('secret') == env("VK_API_GROUP_SECRET") &&
            isset($requestArray['object']['text']) &&
            isset($requestArray['object']['from_id']) &&
            isset($requestArray['object']['peer_id']) &&
            ($r = preg_match($reg, $requestArray['object']['text'], $matches))
        ) {

            if ($requestArray['object']['from_id'] != env("VK_GROUP_ADMIN_ID") && $matches != 'stat') {
                \Illuminate\Support\Facades\Log::error('mamkinCoolHacker', [$requestVk]);
                return 'ok';
            }

            Illuminate\Support\Facades\Log::debug('test', ['r' => $r, 'matches' => $matches]);
            die;

            switch ($matches[2]) {
                case 'echo':
                    //отвечает тем же
                    \Illuminate\Support\Facades\Log::info('echo', $matches);
                    break;
                case 'startEvent':
                    App::call('App\Http\Controllers\Bot@registerChatForCompetitions', ['peerId' => 20000003]);
                    \Illuminate\Support\Facades\Log::info('startEvent', $matches);
                    break;
                case 'stat':
                    \Illuminate\Support\Facades\Log::info('startEvent', $matches);
                    break;
                default:
                    break;
            }


            Illuminate\Support\Facades\Log::debug('object', $requestArray['object']);

            // Illuminate\Support\Facades\Log::info($requestVk->all());
        }

        //регулярка для командлы
        //[club176146542|Роланд Оплотов] startEvent;
        //checkId мой


        return 'ok';
    } catch (Exception $exception) {
        \Illuminate\Support\Facades\Log::error('error', ['message' => $exception->getMessage()]);
        return '{"status":"error"}';
    }
});
