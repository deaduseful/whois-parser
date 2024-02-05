<?php

namespace Deaduseful\WhoisParser;

use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{
    public function rules(): array
    {
        return [
            'query' => ['required', 'string'],
        ];
    }
}
