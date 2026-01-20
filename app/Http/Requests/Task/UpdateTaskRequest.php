<?php

namespace App\Http\Requests\Task;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
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
            "name" => "required|string|max:255",
            "status_id" => "required|exists:task_statuses,id",
            "description" => "string|nullable",
            "assigned_to_id" => "nullable|exists:users,id",
            "labels" => "nullable|array",
            "labels.*" => "exists:labels,id",
        ];
    }
    public function messages()
    {
        return [
            "name.required" => "Это обязательное поле",
            "name.max" => "Название не может быть длиннее 255 символов",
            "status_id.required" => "Это обязательное поле",
            "status_id.exists" => "Статус не найден"
        ];
    }
}
