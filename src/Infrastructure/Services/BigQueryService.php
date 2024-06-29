<?php

namespace App\Infrastructure\Services;

use Google\Cloud\BigQuery\BigQueryClient;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class BigQueryService
{
    protected BigQueryClient $client;
    public function __construct(
        #[Autowire(env: 'GOOGLE_PROJECT_ID')]
        string $projectId,
    ) {
        $this->client = new BigQueryClient([
            'projectId' =>  $projectId,
        ]);
    }

    public function executeQuery(string $query, array $parameters = []) : array
    {
        $queryJobConfig = $this->client->query($query)->parameters($parameters);
        $queryResults = $this->client->runQuery($queryJobConfig);

        if (!$queryResults->isComplete()) {
            throw new \Exception('Unable to complete the query');
        }

        $data = [];
        foreach ($queryResults->rows() as $row) {
            $data[] = $row;
        }

        return $data;
    }
}