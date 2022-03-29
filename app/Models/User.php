<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\User
 *
 * @property string $hrep_id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $middle_name
 * @property string $email
 * @property string|null $mobile_number
 * @property string $password
 * @property string $sex
 * @property string|null $profile_picture_url
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereHrepId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMobileNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfilePictureUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin Eloquent
 * @property string|null $birthday
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBirthday($value)
 * @property int $id
 * @property string|null $home_address
 * @property string|null $barangay
 * @property string|null $city
 * @property string|null $region
 * @property-read Collection|Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBarangay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereHomeAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRegion($value)
 * @property-read UserInfo|null $userInfo
 */
class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable, HasRoles;


  /**
   * The attributes that are mass assignable.
   *
   * @var string[]
   */
  protected $fillable = [
    // 'first_name',
    // 'last_name',
    // 'middle_name',
    // 'email',
    //'password',
    // 'mobile_number',
    // 'sex',
    // 'profile_picture_url',
    // 'birthday',
    // 'home_address',
    // 'barangay',
    // 'city',
    // 'region',
    'hrep_id',
    'password',
    'user_roles'
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'created_at', 'updated_at', 'roles'
  ];

  /**
   * Eager loading with a related model
   *
   * @var string[]
   */
  protected $with = ['userInfo'];

  /**
   * Every User model has exactly one UserInfo
   *
   * @return HasOne
   */
  public function userInfo()
  {
    return $this->hasOne(UserInfo::class);
  }

  /**
   * Move the user_info fields to the root node (User).
   * Only used when adding the model to a response
   * @return User
   */
  public function flattenUserInfo()
  {
    $userInfo = $this->userInfo;
    unset($this->userInfo);

    // $this->user_id =  $userInfo->["id"];
    // $this->first_name =  $userInfo->first_name;
    // $this->last_name =  $userInfo->last_name;
    // $this->middle_name =  $userInfo->middle_name;
    // $this->email =  $userInfo->email;
    // $this->mobile_number =  $userInfo->mobile_number;
    // $this->office_id =  $userInfo->office_id;
    // $this->sex =  $userInfo->sex;
    // $this->profile_picture_url =  $userInfo->profile_picture_url;
    // $this->id_picture_url =  $userInfo->id_picture_url;
    // $this->birthday =  $userInfo->birthday;
    // $this->home_address =  $userInfo->home_address;
    // $this->barangay =  $userInfo->barangay;
    // $this->city =  $userInfo->city;
    // $this->region =  $userInfo->region;
    //  $this->user_roles =  $userInfo->user_roles;

    return $this;
  }
}
