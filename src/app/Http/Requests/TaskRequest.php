<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date|after_or_equal:today',
            'status' => 'required|string|in:pending,in_progress,completed'
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'título',
            'description' => 'descrição',
            'due_date' => 'data de vencimento'
        ];
    }

    public function messages(): array
    {
        return[
            'title.required' => 'O título da tarefa é obrigatório.',
            'title.string' => 'O título deve ter um nome válido.',
            'title.max' => 'O título pode ter no máximo 255 caracteres.',

            'description.string' => 'A descrição deve ter um texto válido.',

            'due_date.required' => 'A data de vencimento é obrigatória.',
            'due_date.date' => 'A data de vencimento deve ser uma data válida.',
            'due_date.after_or_equal' => 'A data de vencimento deve ser no máximo hoje.',

            'status.required' => 'O status da tarefa é obrigatório.',
        ];
    }
}