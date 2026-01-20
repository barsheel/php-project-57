<?php

namespace App\Http\Requests\TaskStatus;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskStatusRequest extends FormRequest
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
            'name' => 'required|unique:task_statuses,name'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => "Это обязательное поле",
            'name.unique' => "Статус с таким именем уже существует"
        ];
    }
}
