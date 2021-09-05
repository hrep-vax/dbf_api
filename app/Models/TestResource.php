<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\TestResource
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TestResource newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TestResource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TestResource query()
 * @method static \Illuminate\Database\Eloquent\Builder|TestResource whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestResource whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestResource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestResource whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestResource whereUpdatedAt($value)
 * @mixin Eloquent
 */
class TestResource extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];
}
