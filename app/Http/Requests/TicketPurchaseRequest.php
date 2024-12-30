<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketPurchaseRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $ticket = $this->route('ticket');

        return [
            'quantity' => 'required|integer|min:1|max:' . $ticket->quantity, // Ensure quantity is valid
        ];
    }

     /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'quantity.required' => 'The quantity field is required.',
            'quantity.integer' => 'The quantity must be a valid integer.',
            'quantity.min' => 'The quantity must be at least 1.',
            'quantity.max' => 'The quantity cannot be greater than the available stock.',
        ];
    }
}
