<?php
/**
 * Created by PhpStorm.
 * User: ashwilliams
 * Date: 12.01.19
 * Time: 23:07
 */

namespace App\Helpers;


class VkApi
{
    private static $vkApiHost = 'https://api.vk.com/method/';
    private static $_self;
    private $token;

    /**
     * @param $chatId
     * @return mixed
     * @throws \Exception
     */
    public function getConversationsMembers($chatId)
    {
        $responseArray = $this->sendRequest('messages.getConversationMembers', ['peer_id' => $chatId]);
        return array_column($responseArray['items'], 'member_id');
    }

    /**
     * @param $chatId
     * @return mixed
     * @throws \Exception
     */
    public function getBdatesConversationsMembers($chatId)
    {
        $responseArray = $this->sendRequest('messages.getConversationMembers', ['peer_id' => $chatId, 'fields' => 'bdate']);
        return $responseArray['profiles'];
    }

    /**
     * @param $peerId
     * @param $message
     * @return mixed
     * @throws \Exception
     */
    public function sendMessage($peerId, $message)
    {
        return $this->sendRequest('messages.send', ['peer_id' => $peerId, 'message' => $message]);
    }

    /**
     * @param $userId
     * @return mixed
     * @throws \Exception
     */
    public function getUserName($userId)
    {
        $user = $this->sendRequest('users.get', ['user_id' => $userId]);
        return reset($user)['first_name'] . ' ' . reset($user)['last_name'];
    }

    /**
     * @param $method
     * @param $parameters
     * @return mixed
     */
    private function sendRequest($method, $parameters)
    {
        $response = file_get_contents(self::$vkApiHost . $method .
            '?' . http_build_query(array_merge($parameters, ['random_id' => rand(10000, 99999), 'access_token' => $this->token, 'v' => 5.95])));

        $responseDecoded = json_decode($response, true);
        //dump($responseDecoded);die;
        if (isset($responseDecoded['error'])) {
            throw new \Error('Ошибка работы с VkApi' . $responseDecoded['error']['error_code'] . ' ' . $responseDecoded['error']['error_msg']);
        }

        return $responseDecoded['response'];
    }

    public static function getInstance($token = null)
    {
        if (is_null(self::$_self)) {
            if (is_null($token)) {
                throw new \Error('The token is null!');
            }
            return new self($token);
        }
        return self::$_self;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token): void
    {
        $this->token = $token;
    }


    /**
     * VkApi constructor.
     * @param $token
     */
    private function __construct($token)
    {
        $this->token = $token;
    }
}