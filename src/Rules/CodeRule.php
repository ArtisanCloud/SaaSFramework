<?php
declare(strict_types=1);

namespace ArtisanCloud\SaaSFramework\Rules;

use ArtisanCloud\SaaSFramework\Services\CodeService\Contracts\CodeServiceContract;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class CodeRule implements Rule
{
    private CodeServiceContract $codeService;

    private int $type;

    private string $to;

    const PHONE_PARAMETER = 'mobile';

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(CodeServiceContract $codeService, string $to, $type)
    {
        $this->to = $to;
        $this->type = $type;
        $this->codeService = $codeService;

    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->codeService->verify($this->to, $value, $this->type);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans("messages." . API_ERR_CODE_INVALID_VERIFY_CODE);
    }
}
