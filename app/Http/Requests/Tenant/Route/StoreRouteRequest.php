<?php

namespace App\Http\Requests\Tenant\Route;

use App\Enums\Tenant\Plant\Operation\Status as OperationStatus;
use App\Enums\Tenant\Plant\Operation\Task\Status as TaskStatus;
use App\Enums\Tenant\Plant\Status as PlantStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRouteRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'alpha_dash',
                'max:255',
                Rule::unique('routes')
                    ->where('tenant_id', tenant('id'))
            ],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'boolean'],
            'tasks' => ['required', 'array'],
            'tasks.*.plant_id' => [
                'required',
                Rule::exists('plants', 'id')
                    ->withoutTrashed()
                    ->where('status', PlantStatus::ACTIVE->value)
                    ->where('tenant_id', tenant('id'))
            ],
            'tasks.*.operation_id' => [
                'required',
                Rule::exists('operations', 'id')
                    ->withoutTrashed()
                    ->where('status', OperationStatus::ACTIVE->value)
                    ->where('tenant_id', tenant('id'))
            ],
            'tasks.*.task_id' => [
                'required',
                'distinct',
                Rule::exists('tasks', 'id')
                    ->withoutTrashed()
                    ->where('status', TaskStatus::ACTIVE->value)
                    ->where('tenant_id', tenant('id'))
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'tasks.*.plant_id' => 'plant',
            'tasks.*.operation_id' => 'operation',
            'tasks.*.task_id' => 'task',
        ];
    }
}
