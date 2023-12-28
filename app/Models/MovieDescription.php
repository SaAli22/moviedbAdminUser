<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovieDescription extends Model
{
    protected $fillable = ['movie_id', 'description'];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
