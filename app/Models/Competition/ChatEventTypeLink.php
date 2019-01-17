<?php

namespace App\Models\Competition;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Competition\ChatEventTypeLink
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Competition\ChatEventTypeLink newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Competition\ChatEventTypeLink newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Competition\ChatEventTypeLink query()
 * @mixin \Eloquent
 * @property int $chat_id
 * @property int $event_type_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Competition\ChatEventTypeLink whereChatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Competition\ChatEventTypeLink whereEventTypeId($value)
 */
class ChatEventTypeLink extends Model
{
    protected $table = 'competition_chat_event_type_link';
    protected $fillable = ['chat_id', 'event_type_id'];
    public $incrementing = false;
    public $timestamps = false;
}
