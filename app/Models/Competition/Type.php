<?php

namespace App\Models\Competition;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Competition\Type
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Competition\Type newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Competition\Type newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Competition\Type query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $competition_type_name
 * @property int $competition_active
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Competition\Type whereCompetitionActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Competition\Type whereCompetitionTypeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Competition\Type whereId($value)
 */
class Type extends Model
{
    protected $table = 'competition_type';
    public $incrementing = false;
    public $timestamps = false;

}
