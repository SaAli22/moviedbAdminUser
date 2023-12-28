<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $table = 'movies'; // Specify the table name explicitly


    protected $fillable = [
        'title', 'description', 'release_date', // Add other fields here
    ];

    // Additional model properties, relationships, etc.
    public static function find($id)
    {
    }
}
