<?php
/**
 * Class AuthPasswordReminderRequest
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

namespace App\Http\Requests;

class AuthPasswordReminderRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email'
        ];
    }
}
