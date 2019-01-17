<?php

namespace App\Models\Competition;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Competition\Chat
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Competition\Chat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Competition\Chat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Competition\Chat query()
 * @mixin \Eloquent
 * @property int $id
 * @property int|null $chat_vk_id
 * @property string|null $chat_vk_name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Competition\Chat whereChatVkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Competition\Chat whereChatVkName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Competition\Chat whereId($value)
 */
class Chat extends Model
{
    protected $table = 'competition_chat';
    protected $fillable = ['chat_vk_id'];
    public $incrementing = true;
    public $timestamps = false;
    protected $primaryKey = 'id';

}
