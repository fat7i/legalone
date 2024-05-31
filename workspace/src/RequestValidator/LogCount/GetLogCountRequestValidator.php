<?php

namespace App\RequestValidator\LogCount;

use App\Exception\ValidationErrorException;
use Symfony\Component\Validator\Constraints as Assert;
use App\RequestValidator\AbstractRequestValidator;

class GetLogCountRequestValidator extends AbstractRequestValidator
{
    public function rules(): Assert\Collection
    {
        return new Assert\Collection(
            [
                'serviceNames' => new Assert\Optional([
                    new Assert\Type('array'),
                    new Assert\All([
                        new Assert\Type('string'),
                    ]),
                ]),
                'startDate' => new Assert\Optional([
                    new Assert\Regex([
                        'pattern' => '/^\d{2}-\d{2}-\d{4}$/',
                        'message' => 'Invalid date format. Please use dd-mm-yyyy.',
                    ]),
                ]),
                'endDate' => new Assert\Optional([
                    new Assert\Regex([
                        'pattern' => '/^\d{2}-\d{2}-\d{4}$/',
                        'message' => 'Invalid date format. Please use dd-mm-yyyy.',
                    ]),
                ]),
                'statusCode' => new Assert\Optional([
                    new Assert\Type('integer'),
                    new Assert\GreaterThan(0),
                ]),
            ]
        );
    }

    /**
     * @throws ValidationErrorException
     */
    public function validateFields(): array
    {
        if (!$request = $this->request) {
            return [];
        }

        $requestedStatusCode = (int) $request->get('statusCode');

        $inputs = [
            'serviceNames' => $request->get('serviceNames'),
            'startDate' => $request->get('startDate'),
            'endDate' => $request->get('endDate'),
            'statusCode' => $requestedStatusCode > 0 ? $requestedStatusCode : null,
        ];

        return $this->doValidate($inputs);
    }
}
