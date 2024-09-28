<?php
declare(strict_types=1);

namespace App\Requests;

use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\HttpServer\Request;
use Hyperf\Validation\Contract\ValidatesWhenResolved;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Hyperf\Validation\ValidationException;

abstract class BaseRequest extends Request implements ValidatesWhenResolved
{
    protected array $validated = [];
    public function __construct(
        private ResponseInterface $response,
        private ValidatorFactoryInterface $validator
    )
    {
    }

    public function validateResolved(): void
    {
        $data = [...$this->all()];
        $validator = $this->validator->make($data, $this->rules(), $this->messages());
        $this->validated = $validator->validated();
        if ($validator->fails()) {
            throw new ValidationException($validator, $this->response);
        }
    }

    abstract protected function rules(): array;
    protected function messages(): array
    {
        return [];
    }
}
