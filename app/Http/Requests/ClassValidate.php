<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClassValidate extends FormRequest
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
            'school' => [
                'nullable',
                Rule::in(['ntu', 'nthu', 'nctu']),
            ],
            'type' => 'nullable|alpha_num|max:12',
            'class' => 'nullable|alpha_num|max:12',
            'page' => 'nullable|integer|max:4',
            'msg_page' => 'nullable|integer|max:4',
            'class_per_page' => 'nullable|integer|in([25, 50,100])',
            'title_per_page' => 'nullable|integer|in([25, 50,100])',
            'msg_per_page' => 'nullable|integer|in([25, 50,100])',
            'classId' => 'sometimes|alpha_num|max:12',
            'prefer' => [
                'sometimes',
                Rule::in(['like', 'dislike']),
            ],
        ];
    }
}
