<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User query()
 * @mixin \Eloquent
 * @property int $id
 * @property int|null $user_vkontakte_id
 * @property string|null $user_name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUserName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUserVkontakteId($value)
 */
class User extends Model
{
    protected $table = 'user';
    protected $fillable = ['user_vkontakte_id', 'user_name'];
    public $incrementing = true;
    public $timestamps = false;
    protected $primaryKey = 'id';


    public static function getRandomId($usersIdsOriginal = array())
    {
        $usersIds = [];

        foreach ($usersIdsOriginal as $item) {
            $usersIds[] = $item;
        }

        shuffle($usersIds);
        $iterationsCount = mt_rand(50, 1000);
        //shuffling
        for ($i = 0; $i < $iterationsCount; $i++) {
            $shuffled = [];
            //for each element of ids array, we'll take random element to buffer array and then we'll unset it from stack.
            for ($j = count($usersIds) - 1; $j >= 0; $j--) {
                $needle = mt_rand(0, $j);
                $shuffled[] = $usersIds[$needle];
                unset($usersIds[$needle]);
                $usersIds = array_values($usersIds);
            }
            $usersIds = $shuffled;
        }

        for ($i = 0; $i < mt_rand(1, 23); $i++) {
            shuffle($usersIds);
        }


        return reset($usersIds);
    }
}
