<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

        protected $table = 'quotes';

        protected $fillable = [
            'quote',
            'author',
            'created_at',
        ];
    
        protected $dates = [
            'created_at',
        ];
    
        public $timestamps = false;
}