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


Route::get('/latest', function (Request $request) {
    $base = $request->query->get('base');
    $response = file_get_contents('https://api.exchangeratesapi.io/latest?base=' . $base);
    $result = json_decode($response);
    if (!is_null($result)) {
        return response()
            ->json($result);
    } else {

        $raw = "{\"base\":\"USD\",\"date\":\"" . Carbon\Carbon::now()->format('Y-m-d') . "\",\"rates\":{\"ISK\":119.7778952935,\"CAD\":1.3314824608,\"MXN\":18.9093953816,\"CHF\":0.9968270756,\"AUD\":1.4048122686,\"CNY\":6.7600035255,\"GBP\":0.7630883131,\"USD\":1.0,\"SEK\":9.0763264587,\"NOK\":8.5532346201,\"TRY\":5.2711087608,\"IDR\":14084.9991186321,\"ZAR\":13.652388507,\"HRK\":6.5498854222,\"EUR\":0.881367883,\"HKD\":7.8460250308,\"ILS\":3.6791820906,\"NZD\":1.4736471003,\"MYR\":4.1325577296,\"JPY\":109.9242023621,\"CZK\":22.6485104883,\"SGD\":1.3566014454,\"RUB\":66.1196016217,\"RON\":4.2030671602,\"HUF\":280.504142429,\"BGN\":1.7237793055,\"INR\":71.0682178741,\"KRW\":1120.1040014102,\"DKK\":6.5806451613,\"THB\":31.6199541689,\"PHP\":52.5841706328,\"PLN\":3.7819495858,\"BRL\":3.7667019214}}";
        $a = json_decode($raw);
        return response()
            ->json($a);
    }
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

    return App::call('App\Http\Controllers\Bot@generateBornDateConratulationsAction'
        , ['peerId' => 2000000003]
    );
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
