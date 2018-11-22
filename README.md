# Elastic APM PHP agent

[![Packagist](https://img.shields.io/packagist/v/samuelbednarcik/elastic-apm-agent.svg)](https://packagist.org/packages/samuelbednarcik/elastic-apm-agent)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/samuelbednarcik/elastic-apm-php-agent.svg)](https://scrutinizer-ci.com/g/samuelbednarcik/elastic-apm-php-agent/)
![PHP from Packagist](https://img.shields.io/packagist/php-v/samuelbednarcik/elastic-apm-agent.svg)

Unofficial PHP agent for
[Elastic APM](https://www.elastic.co/solutions/apm) (>=6.5).

This package also ships with the helpers for easy integration with your
existing project and libraries like Doctrine, Guzzle etc.

**Collectors:**
* [guzzle](https://github.com/samuelbednarcik/guzzle-elastic-apm-collector)
* [doctrine](https://github.com/samuelbednarcik/doctrine-elastic-apm-collector)

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
    new Client(),
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

## Span Collectors

Span collectors are used for extracting informations about events which
happens in the external libraries like doctrine or guzzle.

You can register collectors when creating an agent instance
```php
$agent = new Agent(
    $config,
    new Client(),
    new ElasticAPMSerializer(),
    [
        new MyCollector()
    ]
);
```

Agent will collect spans from all registered collectors after calling
the stop method.

## Distributed tracing

Distributed tracing headers are automatically handled by the agent, the
only thing you have to do is to send `elastic-traceparent-header` in
request which you want to track.
```php
$traceparent = new TraceParent(
    $transaction->getTraceId(),
    $transaction->getId(),
    '01'
);

$request->withHeader(
    TraceParent::HEADER_NAME,
    $traceparent->__toString()
);
```


If you are using Guzzle client, you can use `TracingGuzzleMiddleware`
which will inject header for you.
```php
$middleware = new TracingGuzzleMiddleware($agent)

$stack = HandlerStack::create();
$stack->push($middleware());
$client = new Client(['handler' => $stack])
```

## License
[MIT](https://choosealicense.com/licenses/mit/)