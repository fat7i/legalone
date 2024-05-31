<?php

namespace App\RequestValidator;

use App\Exception\ValidationErrorException;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

abstract class AbstractRequestValidator
{
    protected ?Request $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * Should map all constraints which should be evaluated.
     */
    abstract public function rules(): Assert\Collection;

    /**
     * Should fields from request call the validation and return the result.
     *
     * @return array<string,mixed>
     */
    abstract public function validateFields(): array;

    /**
     * Receive the input fields and validate them.
     *
     * @param array<string,mixed> $inputs
     *
     * @return array<string,mixed>
     *
     * @throws ValidationErrorException
     * @throws Exception
     */
    protected function doValidate(array $inputs): array
    {
        $validator = Validation::createValidator();

        if (key_exists('fields', $inputs)) {
            throw new Exception("the key 'fields' overwrites a Symfony Constrains Param. Pick other field name");
        }
        $violations = $validator->validate($inputs, $this->rules());

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $key = str_ireplace(['[', ']'], '', $violation->getPropertyPath());
                $errors[$key] = $violation->getMessage();
            }

            throw new ValidationErrorException((string) json_encode($errors));
        }

        return $inputs;
    }

    public function getFromBody($keyName): mixed
    {
        return $this->request->toArray()[$keyName] ?? null;
    }
}
