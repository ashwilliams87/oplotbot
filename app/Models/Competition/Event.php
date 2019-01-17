<?php

namespace App\Models\Competition;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Competition\Event
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Competition\Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Competition\Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Competition\Event query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $competition_type_id
 * @property string $competition_date
 * @property int $competition_chat_id
 * @property int $competition_user_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Competition\Event whereCompetitionChatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Competition\Event whereCompetitionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Competition\Event whereCompetitionTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Competition\Event whereCompetitionUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Competition\Event whereId($value)
 */
class Event extends Model
{
    protected $table = 'competition_event';
    protected $fillable = [
        'competition_type_id',
        'competition_chat_id',
        'competition_user_id'
    ];
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $incrementing = true;
}
