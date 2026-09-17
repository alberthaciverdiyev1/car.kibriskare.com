<?php

namespace App\Modules\Car\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'brand_id' => ['required', 'exists:car_brands,id'],
            'model_id' => ['required', 'exists:car_models,id'],
            'body_type_id' => ['nullable', 'exists:car_body_types,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'district_id' => ['nullable', 'exists:districts,id'],
            'deal_type' => ['required', 'in:sale,rent_daily,rent_monthly'],
            'year' => ['required', 'integer', 'min:1950', 'max:' . ((int)date('Y') + 1)],
            'mileage' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:1'],
            'currency' => ['nullable', 'string', 'in:GBP,TRY,EUR,USD'],
            'fuel_type' => ['required', 'string', 'in:petrol,diesel,hybrid,plug_in_hybrid,electric,lpg'],
            'transmission' => ['required', 'string', 'in:automatic,manual,robot,cvt'],
            'steering_wheel' => ['required', 'string', 'in:right,left'],
            'drivetrain' => ['nullable', 'string', 'in:front_wheel,rear_wheel,all_wheel'],
            'engine_volume' => ['nullable', 'integer', 'min:0', 'max:12000'],
            'engine_power' => ['nullable', 'integer', 'min:0', 'max:2000'],
            'color' => ['nullable', 'string', 'max:50'],
            'is_metallic' => ['nullable', 'boolean'],
            'doors' => ['nullable', 'integer', 'min:2', 'max:6'],
            'seats' => ['nullable', 'integer', 'min:1', 'max:20'],
            'condition' => ['required', 'string', 'in:new,used,damaged,for_parts'],
            'is_customs_cleared' => ['nullable', 'boolean'],
            'is_credit_available' => ['nullable', 'boolean'],
            'is_barter_available' => ['nullable', 'boolean'],
            'vin' => ['nullable', 'string', 'max:50'],
            'seller_type' => ['required', 'string', 'in:owner,dealer'],
            'contact_name' => ['required', 'string', 'max:100'],
            'contact_phone' => ['required', 'string', 'max:50'],
            'contact_whatsapp' => ['nullable', 'string', 'max:50'],
            'contact_email' => ['nullable', 'email', 'max:100'],
            'description' => ['nullable', 'string', 'max:5000'],
            'features' => ['nullable', 'array'],
            'features.*' => ['exists:car_features,id'],
            'images' => ['nullable', 'array', 'max:20'],
            'images.*' => ['image', 'mimes:jpeg,jpg,png,webp', 'max:10240'],
        ];
    }
}
