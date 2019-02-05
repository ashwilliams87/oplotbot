<?php

namespace App\Http\Controllers;

use App\Helpers\VkApi;
use App\Models\Competition\Chat;
use App\Models\Competition\Event;
use App\Models\User;
use Illuminate\Support\Carbon;
use PhpParser\Error;

class Bot extends Controller
{
    private static $excludedIds = [44418, 373336876, 290359383,-176146542];

    /**
     * Store a new user.
     *
     * @param $peerId
     * @return bool
     * @throws \Exception
     */
    public function sendMessageAction($peerId)
    {
        //get array of conversation members
        $vkService = VkApi::getInstance(env('VK_API_GROUP_TOKEN'));
        $vkService->sendMessage($peerId, 'echo!');
        return true;
    }

    /**
     * Store a new user.
     *
     * @param $peerId
     * @return bool
     * @throws \Exception
     */
    public function testAction($peerId)
    {
        //get array of conversation members
        $vkService = VkApi::getInstance(env('VK_API_GROUP_TOKEN'));
        $vkService->getConversationsMembers($peerId, 'echo!');
        return true;
    }

    public function registerChatForCompetitionsAction($peerId)
    {
        try {
            /** @var Chat $chat */
            $chat = Chat::where('chat_vk_id', $peerId)->first();
            if (empty($chat)) {
                $chat = Chat::firstOrNew(['chat_vk_id' => $peerId]);
                $chat->save();
            }
            return true;
        } catch (\Error $e) {
            \Illuminate\Support\Facades\Log::error('error', ['message' => $e->getMessage()]);
            return false;
        }
    }


    /**
     * @param $peerId
     * @param null $chatId
     * @return void
     */
    public function generateEventAction($peerId = null, $chatId = null)
    {
        $queryChats = Chat::query();

        if ($peerId) {
            $queryChats->where('competition_chat.chat_vk_id', '=', $peerId);
        }

        if ($chatId) {
            $queryChats->where('competition_chat.id', '=', $chatId);
        }

        $chats = $queryChats
            //->whereIn('competition_chat.id', [1, 2])
            ->select(
                'competition_chat.id as chat_id',
                'competition_chat.chat_vk_id',
                'competition_type.id as competition_type_id',
                'competition_type.competition_type_name'
            )
            ->join('competition_chat_event_type_link', 'competition_chat_event_type_link.chat_id', '=', 'competition_chat.id')
            ->join('competition_type', 'competition_type.id', '=', 'competition_chat_event_type_link.event_type_id')
            ->orderBy('chat_vk_id', 'ASC')
            ->get();

        if (empty($chats)) {
            throw new \Error('No registered chats to generate event');
        }
        foreach ($chats as $chat) {
            try {

                $this->makeEvent($chat->chat_id, $chat->chat_vk_id, $chat->competition_type_id, $chat->competition_type_name);
                sleep(30);

            } catch (\Error $e) {
                \Illuminate\Support\Facades\Log::error('error', ['message' => $e->getMessage()]);
                continue;
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('error', ['message' => $e->getMessage()]);
                continue;
            }
        }

    }

    /**
     * @param $peerId
     * @param null $chatId
     * @return void
     */
    public function generateBornDateConratulationsAction($peerId = null, $chatId = null)
    {
        $queryChats = Chat::query();

        if ($peerId) {
            $queryChats->where('competition_chat.chat_vk_id', '=', $peerId);
        }

        if ($chatId) {
            $queryChats->where('competition_chat.id', '=', $chatId);
        }

        $chats = $queryChats
            //->whereIn('competition_chat.id', [1, 2])
            ->where('competition_chat.chat_active', '=', 1)
            ->select(
                'competition_chat.id as chat_id',
                'competition_chat.chat_vk_id',
                'competition_type.id as competition_type_id',
                'competition_type.competition_type_name'
            )
            ->join('competition_chat_event_type_link', 'competition_chat_event_type_link.chat_id', '=', 'competition_chat.id')
            ->join('competition_type', 'competition_type.id', '=', 'competition_chat_event_type_link.event_type_id')
            ->orderBy('chat_vk_id', 'ASC')
            ->get();

        if (empty($chats)) {
            throw new \Error('No registered chats to generate event');
        }

        foreach ($chats as $chat) {
            try {

                $this->makeCongratulations($chat->chat_id, $chat->chat_vk_id, $chat->competition_type_id, $chat->competition_type_name);
                sleep(30);

            } catch (\Error $e) {
                \Illuminate\Support\Facades\Log::error('error', ['message' => $e->getMessage()]);
                continue;
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('error', ['message' => $e->getMessage()]);
                continue;
            }
        }

    }


    private function makeEvent($chatId, $chatVkId, $competitionTypeId, $competitionTypeName)
    {
        //get array of conversation members
        $vkService = VkApi::getInstance(env('VK_API_USER_TOKEN'));
        $usersIdsNotFiltered = array_diff($vkService->getConversationsMembers($chatVkId), self::$excludedIds);

        //we need filter peolple who doesn't want to be a winner
        $usersIds = [];
        foreach ($usersIdsNotFiltered as $itemUserId) {
            if (!in_array($itemUserId, self::$excludedIds)) {
                $usersIds[] = $itemUserId;
            }
        }

        //lets the event begin
        $winnerId = User::getRandomId($usersIds);
        $userName = $vkService->getUserName($winnerId);

        if (empty($userName)) {
            throw  new Error('Empty name, may be it\'s wrong user ID');
        }

        $user = User::query()->where('user_vkontakte_id', $winnerId)->first();
        if (empty($user)) {
            $user = User::create(['user_vkontakte_id' => $winnerId, 'user_name' => $userName]);
        }
        $vkService->sendMessage($chatVkId, 'Итак! Пришло время узнать....');
        sleep(7);
        $vkService->sendMessage($chatVkId, 'Кто же у нас сегодня ' . $competitionTypeName . '.');
        sleep(7);
        $vkService->sendMessage($chatVkId, 'такс такс такс... што тут у нас...');
        sleep(7);

        Event::create([
            'competition_type_id' => $competitionTypeId,
            'competition_chat_id' => $chatId,
            'competition_user_id' => $user->id,
        ])->save();

        sleep(7);
        $vkService->sendMessage($chatVkId, 'Ага!');
        sleep(7);
        $vkService->sendMessage($chatVkId, $competitionTypeName . ': [id' . $user->user_vkontakte_id . '|' . $userName . '] ' . 'Поздравляем!!!');

        switch ($user->user_vkontakte_id) {
            case 14363074:
                $vkService->sendMessage($chatVkId, 'Соррян, ничего личного... ' . $competitionTypeName . ' мой разработчик! Искренне поздравляю, от всего исходного кода!');
                break;
            default:
                break;
        }

        return;
    }


    private function makeCongratulations($chatId, $chatVkId, $competitionTypeId, $competitionTypeName)
    {
        //get array of conversation members
        $vkService = VkApi::getInstance(env('VK_API_USER_TOKEN'));
        $chatUsers = $vkService->getBdatesConversationsMembers($chatVkId);
        $today = Carbon::now()->format('j.n');

        $congraz = [];
        foreach ($chatUsers as $key => $user) {
            if (isset($user['bdate'])) {
                $match = [];
                preg_match('/(\d{1,2}.\d{1,2}).\d+/', $user['bdate'], $match);
                //если нашли совпадение по дате, и юзер не исключен
                if (isset($match[1]) && $today == $match[1] && !in_array($user['id'], self::$excludedIds)) {
                    $congraz[] = '[id' . $user['id'] . '|' . $user['first_name'] . ' ' . $user['last_name'] . ']';
                }
            }
        }

        //we need filter peolple who doesn't want to be a winner
        if (!empty($congraz)) {
            $vkService->sendMessage($chatVkId, 'Ого! Сегодня у кого-то день рождения!');
            sleep(7);
            $vkService->sendMessage($chatVkId, 'такс такс такс... што тут у нас...');
            sleep(7);
            $vkService->sendMessage($chatVkId, 'Ага!');
            sleep(7);
            $vkService->sendMessage($chatVkId, 'Сегодня именинник' . (count($congraz) > 1 ? 'и' : '') . ': ' . implode(', ', $congraz));
            sleep(7);
            $vkService->sendMessage($chatVkId, 'Искренне поздравляю от всего исходного кода! Желаю крепкого здоровья, исполнения всех желаний, эффективного достижения целей! Поздравляем!');
        }

        return;
    }


}