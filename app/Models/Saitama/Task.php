<?php

namespace App\Models\Saitama;
use Illuminate\Database\Eloquent\Model;


class Task extends Model
{
    protected $table = 'saitama_task';
    protected $fillable = ['task_name'];
    public $incrementing = true;
    public $timestamps = false;
    protected $primaryKey = 'id';
}
