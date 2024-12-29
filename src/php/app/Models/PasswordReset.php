<?php
/**
 * Class PasswordReset
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $table = 'password_reset_tokens';
    protected $primaryKey = 'email'; 

    const UPDATED_AT = null;

    protected $fillable = [
        'email',
        'token'
    ];
}
