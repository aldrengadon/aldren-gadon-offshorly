<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todos extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title_id',
        'todo',
        'is_done'
    ];


    public function toDosTitle(){
        return $this->belongsTo(
            Title::class, 
            'id', //foreign key
            'title_id' //local key
        );
    }
}
