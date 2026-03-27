<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_name' => ['required', 'string', 'max:200'],
            'status' => ['required', 'in:planning,in_progress,on_hold,completed,cancelled'],
            'budget_allocated' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
