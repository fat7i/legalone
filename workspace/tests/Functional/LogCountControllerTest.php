<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class LogCountControllerTest extends WebTestCase
{
    private const GET_COUNT_URI = '/count';

    public function validGetCountRequestProvider(): array
    {
        return [
            [self::GET_COUNT_URI],
            [self::GET_COUNT_URI . '?statusCode=201'],
            [self::GET_COUNT_URI . '?serviceNames[0]=USER-SERVICE'],
            [self::GET_COUNT_URI . '?serviceNames[0]=USER-SERVICE&serviceNames[1]=INVOICE-SERVICE'],
            [self::GET_COUNT_URI . '?startDate=20-10-2023'],
            [self::GET_COUNT_URI . '?endDate=20-10-2023'],
            [self::GET_COUNT_URI . '?startDate=20-10-2023&endDate=21-10-2023'],
            [self::GET_COUNT_URI . '?statusCode=201&serviceNames[0]=USER-SERVICE&serviceNames[1]=INVOICE-SERVICE&startDate=20-10-2023&endDate=21-10-2023'],
        ];
    }

    /**
     * @dataProvider validGetCountRequestProvider
     */
    public function testValidGetCountRequest(string $validUri): void
    {
        $client = static::createClient();
        $client->request('GET', $validUri, [], [], ['CONTENT_TYPE' => 'application/json']);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $responseData = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertArrayHasKey('counter', $responseData);
    }

    public function invalidGetCountRequestProvider(): array
    {
        return [
            [self::GET_COUNT_URI . '?serviceNames=USER-SERVICE'],
            [self::GET_COUNT_URI . '?serviceNames=USER-SERVICE&serviceNames=INVOICE-SERVICE'],
            [self::GET_COUNT_URI . '?startDate=20/10/2023'],
            [self::GET_COUNT_URI . '?endDate=21/10/2023'],
            [self::GET_COUNT_URI . '?startDate=2023/10/20'],
            [self::GET_COUNT_URI . '?endDate=2023/10/21'],
        ];
    }

    /**
     * @dataProvider invalidGetCountRequestProvider
     */
    public function testInvalidGetCountRequest(string $invalidUri): void
    {
        $client = static::createClient();
        $client->request('GET', $invalidUri, [], [], ['CONTENT_TYPE' => 'application/json']);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $responseData = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertArrayHasKey('errors', $responseData);
    }

    public function InvalidRequestMethodProvider(): array
    {
        return [
            ['POST'],
            ['PUT'],
            ['DELETE'],
            ['PATCH'],
        ];
    }

    /**
     * @dataProvider InvalidRequestMethodProvider
     */
    public function testInvalidRequestMethod(string $invalidMethod): void
    {
        $client = static::createClient();
        $client->request($invalidMethod, '/count', [], [], ['CONTENT_TYPE' => 'application/json']);

        $this->assertEquals(Response::HTTP_METHOD_NOT_ALLOWED, $client->getResponse()->getStatusCode());
    }
}