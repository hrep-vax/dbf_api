<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Check extends Model
{
  use HasFactory;

  protected $fillable = [
    'code',
    'check',
    'amount',
    'che_date'
  ];

  public function payee()
  {
    return $this->belongsTo(Payee::class);
  }
}
