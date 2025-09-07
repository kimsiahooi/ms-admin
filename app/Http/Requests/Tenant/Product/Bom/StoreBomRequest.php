<?php

namespace App\Http\Requests\Tenant\Product\Bom;

use App\Enums\Tenant\Material\Status as MaterialStatus;
use App\Enums\Tenant\Material\UnitType;
use App\Enums\Tenant\Product\Bom\Status;
use App\Models\Tenant\Material;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreBomRequest extends FormRequest
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
                'max:255',
                Rule::unique('boms')
                    ->where('product_id', $this->product->id)
                    ->where('tenant_id', $this->product->tenant_id)
                    ->where('tenant_id', tenant('id'))
            ],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'boolean'],
            'materials' => ['required', 'array'],
            'materials.*.id' => [
                'required',
                'distinct',
                Rule::exists('materials')
                    ->where('status', MaterialStatus::ACTIVE->value)
                    ->where('tenant_id', $this->product->tenant_id)
                    ->where('tenant_id', tenant('id'))

            ],
            'materials.*.quantity' => ['required', 'numeric', 'decimal:0,2', 'min:0.01'],
            'materials.*.unit_type' => [
                'required',
                Rule::enum(UnitType::class),
            ],
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            foreach ($this->materials as $i => $material) {
                $unitType = Material::where('id', $material['id'])->value('unit_type');
                if ($unitType !== $material['unit_type']) {
                    $validator->errors()->add(
                        "materials.$i.unit_type",
                        "Unit type must match the material's defined unit type."
                    );
                }
            }
        });
    }
}
