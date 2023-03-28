<?php

namespace App\Models;

use App\Commons\Constants;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;
use Laravel\Passport\HasApiTokens;
use App\Models\Base\Model;

class Account extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use MustVerifyEmail;

    protected $table = 'account';

    public const PAGE_TITLE 		= 'Staff Account';
    public const CAN_CREATE 		= true;
    public const TABLE_FIELDS 		= ['username' => 'username', 'first_name' => 'first_name', 'last_name' => 'last_name', 'is_locked' => 'is_locked', 'is_active' => 'is_active'];
    public const ALLOW_ACTIONS 		= ['view', 'edit'];
    public const OPERATION	 		= ['create'];

    public const TYPE_ADMIN = 'admin';
    public const TYPE_STAFF = 'staff';

    public const VIWES_FIELDS = [
        'username'					=> 'normal',
        'first_name' 				=> 'normal',
        'last_name' 				=> 'normal',
        'sex'						=> 'normal',
        'email'		 				=> 'normal',
        'phone' 					=> 'normal',
        'type'	 					=> 'normal',
        'login_failed_count'		=> 'normal',
        'is_locked'				 	=> 'boolean',
        'is_active' 				=> 'boolean',
    ];

    protected $fillable = [
        'username',
        'password',
        'first_name',
        'last_name',
        'sex',
        'email',
        'phone',
        'type',
        'company_id',
    ];

    protected $hidden = [
        'password',
    ];

    private static function getCommonValidatonRules()
    {
        return [
            'username'      => 'required|string|min:5|max:20|unique:account',
            'sex'           => 'required|string',
            'email'         => 'required|string|email|max:255|unique:account',
            'phone'         => 'required|string|max:20',
            'first_name'    => 'string|max:255',
            'last_name'     => 'string|max:255',
            'image_selection' => 'nullable|integer',
        ];
    }

    public static function getValidateRules(int $id = -1)
    {
        $rules = self::getCommonValidatonRules();
        if ($id > 0) {
            $current_account = self::where('id', $id)->first();
            if ($current_account instanceof Account) {
                $rules['username'] = ['string', 'min:5', 'max:20', Rule::unique('account', 'username')->ignore($current_account)];
                $rules['email'] = ['string', 'email', 'max:255', Rule::unique('account', 'email')->ignore($current_account)];
            }
        } else {
            $rules['password'] = sprintf('required|confirmed|regex:/%s/', Constants::PASSWORD_REGEXP);
        }
        return $rules;
    }

    public static function getInpageTitle(int $id = -1)
    {
        return static::findRecord($id)->username;
    }

    public static function matchField($user = null, array $data = [])
    {
        $temp = [];
        if (empty(self::VIWES_FIELDS)) {
            return $data;
        }
        foreach ($data as $key => $value) {
            if (array_key_exists($key, self::VIWES_FIELDS)) {
                $temp[$key] = $value;
            }
            if (in_array($key, ['password']) > 0) {
                $temp[$key] = password_hash($value, PASSWORD_DEFAULT);
            }
        }
        $temp['company_id'] = $user->company_id;
        return $temp;
    }

    public static function getData(int $paginate_size = -1, int $company_id = 0)
    {
        if ($paginate_size > 0) {
            return static::where(['company_id' => $company_id, 'is_delete'=> 0])->paginate($paginate_size);
        }
        return static::where('is_delete', 0)->get();
    }

    public static function getStaffList(int $company_id = 0)
    {
        return static::where(['company_id' => $company_id, 'type' => self::TYPE_STAFF, 'is_delete' => 0])->get();
    }

    public function getUserProfilePicture()
    {
        $usage = ImageUsage::getImages('\App\Models\Account', $this->id);
        if (isset($usage) && is_array($usage) && sizeof($usage) > 0) {
            return array_values($usage)[sizeof($usage) - 1];
        }
        return secure_asset('img/default icon.jpg');
    }
}
