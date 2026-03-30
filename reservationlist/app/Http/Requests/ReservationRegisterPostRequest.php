<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRegisterPostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_name' => ['required','max:128'],
            'staff_name' => ['required'],
            'reservation_date' => ['required','date'],
            'reservation_time' => ['required'],
            'memo' => ['max:1000'],
        ];
    }
}
