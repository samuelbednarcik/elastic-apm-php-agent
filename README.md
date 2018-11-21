# Elastic APM PHP agent

Unofficial PHP agent for [Elastic APM](https://www.elastic.co/solutions/apm).

This package also ships with the helpers for easy integration with your
existing project and libraries like Doctrine, Guzzle etc.

## Installation
```bash
composer require samuelbednarcik/elastic-apm-agent
```

## Usage

Create an agent configuration object
```php
$config = new AgentConfiguration();
$config->setServiceName = 'name-of-your-project';
$config->setServerUrl = 'http://localhost:8200'; // elastic apm server

// create metadata which will be applied to the transaction
$metadata = new Metadata();
$metadata->setService(
    MetadataBuilder::buildService('name-of-your-project')
);
$config->setMetadata($metadata);
```

Create an agent instance
```php
$agent = new Agent(
    $config,
    new \GuzzleHttp\Client(),
    new ElasticAPMSerializer()
);
```

Call start method as soon as possible in your code. Request start time is
retrieved from the **REQUEST_TIME_FLOAT** server variable. If you don't
provide a request instance, it will be created from the global variables.
Start method will also return a transaction instance.
```php
$transaction = $agent->start($request);
```

Call stop function at the end of the code. Optionally, if you are using
symfony request/response, you can use transaction builder to generate
a context for the transaction. By calling the stop method, all spans
from collectors will be collected. This function will also return a transaction.
```php
$transaction = $agent->stop();
$transaction->setContext(
    TransactionBuilder::buildContext($request, $response)
);
```

After that, you can call sendAll method which will send all informations
to APM server.
```php
try {
    $agent->sendAll();
} catch (GuzzleException $e) {
    // log an error
}
```

## License
[MIT](https://choosealicense.com/licenses/mit/)