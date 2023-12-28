<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Watchlist extends Model
{
    use HasFactory;
    public function up()
    {
        Schema::create('watchlists', function (Blueprint $table) {
            $table->id('movie_id');
            $table->id('user_id');

            // Add more fields as needed

            $table->timestamps();
        });
    }
    protected $fillable = [
        'movie_id',
        'user_id',
    ];
}
