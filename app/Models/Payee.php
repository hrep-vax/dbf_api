<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payee extends Model
{
  use HasFactory;

  protected $fillable = [
    'code',
    'payee1'
  ];

  public function check()
  {
    return $this->hasMany(Check::class);
  }
}
