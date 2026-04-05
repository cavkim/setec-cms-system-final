<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Task;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $task = $this->route('task') ?? Task::find($this->route('task_id'));

        return $task && $this->user()->can('update', $task);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'task_name' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:1000'],
            'project_id' => ['required', 'exists:projects,id'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'priority' => ['required', 'in:low,medium,high'],
            'status' => ['required', 'in:pending,in_progress,completed,cancelled'],
            'due_date' => ['nullable', 'date'],
            'progress_percent' => ['nullable', 'integer', 'min:0', 'max:100'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'task_name.required' => 'Task name is required.',
            'task_name.max' => 'Task name cannot exceed 200 characters.',
            'project_id.required' => 'Please select a project.',
            'project_id.exists' => 'The selected project does not exist.',
            'priority.required' => 'Please select a priority level.',
            'priority.in' => 'Priority must be low, medium, or high.',
            'status.required' => 'Please select a status.',
            'status.in' => 'Invalid status selected.',
            'due_date.date' => 'Please provide a valid due date.',
            'progress_percent.min' => 'Progress cannot be negative.',
            'progress_percent.max' => 'Progress cannot exceed 100%.',
        ];
    }
}
