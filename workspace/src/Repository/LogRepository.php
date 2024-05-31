<?php

namespace App\Repository;

use Elastic\Elasticsearch\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Dto\LogFilterDto;

class LogRepository
{
    public function __construct(
        private readonly Client $client,
        private readonly ParameterBagInterface $params,
        private readonly LoggerInterface $logger,
    )
    {
    }

    public function getLogCount(LogFilterDto $logFilterDto): int
    {
        try {
            $searchQuery = $this->getFilterQuery($logFilterDto);
            $results = $this->client->search($searchQuery);

            return $results["hits"]["total"]["value"];
        }  catch (\Throwable $e) {
            $this->logger->error($e->getMessage());

            return 0;
        }
    }

    private function getFilterQuery(LogFilterDto $logFilterDto): array
    {
        $query = [
            'index' => $this->params->get('elasticsearch.index_name'),
            'body'  => [
                'query' => [
                    'bool' => [
                        'must' => [],
                        'should' => [],
                        'minimum_should_match' => 1,
                    ],
                ],
            ],
        ];

        $this->addDateRangeFilter($logFilterDto, $query);
        $this->addStatusCodeFilter($logFilterDto, $query);
        $this->addServiceNamesFilter($logFilterDto, $query);

        return $query;
    }

    private function addDateRangeFilter(LogFilterDto $logFilterDto, array &$query): void
    {
        $rangeQuery = null;

        if ($logFilterDto->startDate) {
            $rangeQuery['range']['@timestamp']['gte'] = $logFilterDto->startDate->format('Y-m-d\TH:i:s');
        }

        if ($logFilterDto->endDate) {
            $rangeQuery['range']['@timestamp']['lte'] = $logFilterDto->endDate->format('Y-m-d\TH:i:s');
        }

        if ($rangeQuery) {
            $query['body']['query']['bool']['must'][] = $rangeQuery;
        }
    }

    private function addStatusCodeFilter(LogFilterDto $logFilterDto, array &$query): void
    {
        if ($logFilterDto->statusCode) {
            $query['body']['query']['bool']['must'][] = [
                'match' => ['response_code' => $logFilterDto->statusCode],
            ];
        }
    }

    private function addServiceNamesFilter(LogFilterDto $logFilterDto, array &$query): void
    {
        if ($logFilterDto->serviceNames) {
            foreach ($logFilterDto->serviceNames as $serviceName) {
                $query['body']['query']['bool']['should'][] = ['match' => ['service.keyword' => $serviceName]];
            }
        } else {
            unset($query['body']['query']['bool']['should'], $query['body']['query']['bool']['minimum_should_match']);
        }
    }
}