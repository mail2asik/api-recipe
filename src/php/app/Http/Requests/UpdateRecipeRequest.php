<?php
/**
 * Class StoreRecipeRequest
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

namespace App\Http\Requests;

class UpdateRecipeRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category' => 'required',
            'title' => 'required|min:10',
            'ingredients' => 'required|min:10',
            'short_desc' => 'required|min:10',
            'long_desc' => 'required|min:20'
        ];
    }
}
