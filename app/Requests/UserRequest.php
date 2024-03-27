<?php

namespace App\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
/**
 * @method all()
 */
class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @param int|null $id
     * @return array
     */
    public function rules(?int $id = null): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email' . ($id ? ",$id" : ''),
            'password' => 'required|string|min:8',
        ];
    }
    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên là bắt buộc',
            'name.string' => 'Tên phải là chuỗi',
            'name.max' => 'Tên không được vượt quá 255 ký tự',
            'email.required' => 'Email là bắt buộc',
            'email.string' => 'Email phải là chuỗi',
            'email.email' => 'Email phải là một địa chỉ email hợp lệ',
            'email.max' => 'Email không được vượt quá 255 ký tự',
            'email.unique' => 'Email đã tồn tại',
            'password.required' => 'Mật khẩu là bắt buộc',
            'password.string' => 'Mật khẩu phải là chuỗi',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
        ];
    }
    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
        ];
    }
    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
