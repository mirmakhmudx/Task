<?php

namespace App\Http\Requests\Cabinet\Dialogs;

use Illuminate\Foundation\Http\FormRequest;

class WriteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message' => 'required|string',
        ];
    }
}
EOFcat > app/Http/Requests/Cabinet/Dialogs/WriteRequest.php << 'EOF'
<?php

namespace App\Http\Requests\Cabinet\Dialogs;

use Illuminate\Foundation\Http\FormRequest;

class WriteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message' => 'required|string',
        ];
    }
}
