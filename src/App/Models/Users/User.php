<?php

namespace GeoSot\BaseAdmin\App\Models\Users;


use GeoSot\BaseAdmin\App\Traits\Eloquent\EnabledDisabled;
use GeoSot\BaseAdmin\App\Traits\Eloquent\HasAllowedToHandleCheck;
use GeoSot\BaseAdmin\App\Traits\Eloquent\HasFrontEndConfigs;
use GeoSot\BaseAdmin\App\Traits\Eloquent\HasImages;
use GeoSot\BaseAdmin\App\Traits\Eloquent\HasRulesOnModel;
use GeoSot\BaseAdmin\App\Traits\Eloquent\ModifiedBy;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lab404\Impersonate\Models\Impersonate;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laratrust\Traits\LaratrustUserTrait;


class User extends Authenticatable implements MustVerifyEmail, HasLocalePreference
{

    use Notifiable, HasApiTokens, SoftDeletes, EnabledDisabled, HasImages, ModifiedBy, LaratrustUserTrait, HasRulesOnModel, HasFrontEndConfigs, HasAllowedToHandleCheck,Impersonate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'gender',
        'dob',
        'phone1',
        'phone2',
        'address',
        'preferred_lang',
        'city',
        'postal',
        'state',
        'country',
        'notes',
        'bio',
        'slack_webhook_url',
        'notification_types',
        'enabled',
        'modified_by',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'dob' => 'date',
        'notification_types' => 'array'
    ];
    protected $appends = [
        'full_name',
    ];
    protected $frontEndConfigValues = [
        'site' => [
            'viewDir' => 'users',
        ],
    ];

    public static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            //   $token = app("auth.password.broker")->createToken($model);
//            $model->notify(new UserCreated($model, $token));
        });
    }

    public function setPasswordAttribute($password)
    {
        if (Hash::needsRehash($password)) {
            $password = Hash::make($password);
        }

        $this->attributes['password'] = $password;
    }

    /**
     * Get the user's preferred locale.
     *
     * @return string
     */
    public function preferredLocale()
    {
        return $this->preferred_lang ?? config('app.locale');
    }

    /**
     * Validation RULES
     *
     * @param  array $merge
     *
     * @return array
     */
    protected function rules(array $merge = [])
    {
        if (is_null($this->id) or !is_null(request()->input('password'))) {
            $merge = array_merge($merge, [
                'password' => 'required|min:6|confirmed'
            ]);
        }
        if (in_array('slack', request()->input('notification_types', []))) {
            $merge = array_merge($merge, [
                'slack_webhook_url' => 'required'
            ]);
        }

        return array_merge([
            'email' => ['required', 'email', 'max:190', "unique:{$this->getTable()},email" . $this->getIgnoreTextOnUpdate(),],
            //            "images.*"      => "required|nullable|mimes:jpg,jpeg,bmp,png|max:100",
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
        ], $merge);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }


    public function routeNotificationForSlack($notification)
    {
        return $this->slack_webhook_url;
    }

    public function canImpersonate()
    {
        return $this->can('admin.*') and !app('impersonate')->isImpersonating();
    }

}
