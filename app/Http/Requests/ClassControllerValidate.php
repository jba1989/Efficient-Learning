<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassControllerValidate extends FormRequest
{
    /**
     * The route-name to redirect to if validation fails.
     *
     * @var string
     */
    protected $redirectRoute = 'class';

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
            'school' => 'sometimes|alpha_num|max:12',
            'type' => 'sometimes|alpha_num|max:12',
            'class' => 'sometimes|alpha_num|max:2',
            'page' => 'sometimes|integer|max:4',
            'msg_page' => 'sometimes|integer|max:4',
            'class_per_page' => 'sometimes|integer|in([25, 50,100])',
            'title_per_page' => 'sometimes|integer|in([25, 50,100])',
            'msg_per_page' => 'sometimes|integer|in([25, 50,100])',
        ];
    }
}
