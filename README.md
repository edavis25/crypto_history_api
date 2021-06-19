# Crypto History API
This project exposes crypto price history I personally scraped from various exchanges into a consistent and extendable API. It provides a layer of abstraction for InfluxDB measurements that have varying schemas and data points.


### Just looking for the API documentation?
[View the API Documentation](https://edavis25.github.io/crypto_history_api/)

___ 
### Looking to stand up own version of the project?
Did you randomly feel compelled to scrape a bunch of historical crypto price data? Did you also decide to use an obscure timeseries database, InfluxDB? Did you finally decide you wanted a consistent API to retrieve the hodge-podge data you scraped? Cool, me too. Let's be friends. Also, keep reading to use this project to stand up an API for your very own InfluxDB data hoard.

## Pre Reqs
This project assumes you have an instance of InfluxDB running a single database that contains different "measurements" (Influx terminology) for recording prices from different exchages. For example, my database contains Open/Close/High/Low/etc data for the Poloniex exchange under a single Influx measurement. Measurements can be loosely thought of as tables and in this case, our table contains historic price data from the Poloniex exchange (and only Poloniex). Different measurements can be created for different exchanges and each record should contain a timestamp, whatever price data you want, and a tag for the trading "pair" (btc_usd).

For example, if you wanted to get price history for btc_usd on the Poloniex exchange, you can imagine an SQL query: `SELECT * FROM poloniex_exchange WHERE pair = 'btc_usd'` for this setup.

TLDR; Create a single Influx database with different measurements for every different exchange's price data. Each record needs to contain whatever price data you want and an [Influx tag](https://docs.influxdata.com/influxdb/v1.8/concepts/glossary/#tag-key) referencing the trading pair. This project assumes you named your tag: `pair` but this is customizable if you are a masochist.

## Installation
1. Clone the repository<br>`git clone https://github.com/edavis25/crypto_history_api.git`
2. Install project dependencies<br>`composer install`
3. Create your environment's configuration file
<br>`cp ./.env.example ./.env`
<br><br> The most important part of this step is configuring your connection to InfluxDB:
```
INFLUXDB_HOST=127.0.0.1
INFLUXDB_PORT=8086
INFLUXDB_SSL=false
INFLUXDB_VERIFYSSL=false
INFLUXDB_TIMEOUT=0
INFLUXDB_DBNAME=crypto_price_history # All measurements must be inside single database
```
4. Run the Laravel migrations.
<br>`php artisan migrate`

## Setup InfluxDB measurements for the API
Now that your environment is setup, we can begin mapping our InfluxDB measurements for use in the API. To do this, we will need to do a few things:
1. Create a new service that fulfills the `InfluxDBMeasurement` contract
2. Create [JSON resources](https://laravel.com/docs/8.x/eloquent-resources) for our response data schemas
3. Map our new service class inside the `influxdb.php` config file.

#### 1. Creating the service
Every InfluxDB measurement will need its own service implementing the `InfluxDBMeasurement` contract so that it can be dynamically injected into the controllers. As a starting point, you can create a class that extends the `BaseMeasurementService` which will fulfill a majority of the contract's functionality.
<br>
```
class PoloniexMeasurementService extends BaseMeasurementService
{
    // the name of the measurement inside of InfluxDB
    public function measurement(): string
    {
        return 'poloniex_exchange';
    }

    // a human-friendly version of the measurement's name for display purposes
    public function displayName(): string
    {
        return 'Poloniex';
    }
    
    // responsible for transforming the data from the measurement's schema into a standardized JSON resource.
    public function buildResource(array $data): JsonResource
    {
        return new PoloniexMeasurement($data);
    }

    // responsible for building a collection of multiple JSON resources.
    public function buildResourceCollection(array $data, bool $has_next_page): ResourceCollection
    {
        return new PoloniexMeasurementCollection($data, $has_next_page);
    }
   
}
```

At the very least, each service will need to implement the methods shown above individually. If you mostly follow the InfluxDB setup explained above, you can rely on the `BaseMeasurementService` to fulfill most of the contract. If you need more customization, you can override the functions inside the `BaseMeasurementService` as necessary.

#### 2. Creating the JSON resources
If you noticed in that last step that we were building resources that didn't exist yet, good eyes. Let's do that now.

If you are like me, you may have various measurements each containing varying data points with different names. The main purpose of creating these resources is to transform data from our InfluxDB measurement schemas into consistent API responses. There isn't really anything fancy going on here, we just need to map the data we want from InfluxDB into [Laravel's native API Resources](https://laravel.com/docs/8.x/eloquent-resources).

```
class PoloniexMeasurement extends JsonResource
{
    // Transform the resource into an array used in the response.
    public function toArray($request)
    {
        return [
            'time'   => (string) $this->resource['time'],
            'pair'   => (string) $this->resource['pair'],
            'open'   => (float) $this->resource['open'],
            'close'  => (float) $this->resource['close'],
            'low'    => (float) $this->resource['low'],
            'high'   => (float) $this->resource['high'],
            'volume_from' => (float) $this->resource['volume'],
            'volume_to' => (float) $this->resource['quote_volume'],
        ];
    }
}
```

// todo fill in some info about creating collections with pagination using the traits.

#### 3. Configuration
The final step is mapping our service to an endpoint in the `influxdb.php` config file. Navigate to the file and look for the `measurements` array. This array will contain key=>value pairs where your measurement is the key and the namespace of your service class is the value:
```
/*
 |--------------------------------------------------------------------------
 | Measurements
 |--------------------------------------------------------------------------
 | ...<removed for brevity>...
*/
 'measurements' => [
    'poloniex_exchange' => \App\Services\PoloniexMeasurementService::class,
 ],
```
Boom! Once your mapping has been created, you can use the API to query your data using the key name as your endpoint:
<br> `127.0.0.1/api/poloniex_exchange/pairs`

#### Route aliases
I know what you're thinking, that URL is ugly? Well I thought so too, and instead of `poloniex_exchange` wouldn't it be cleaner just being: `/api/poloniex/pairs`? Of course it would. To do this, you can use the `route_aliases` array in the same config file. This array will again be key=>pair values where the key becomes the new endpoint and the value contains a reference to the measurement name.
```
/*
|--------------------------------------------------------------------------
| Route Overrides
|--------------------------------------------------------------------------
| ...<removed for brevity>...
*/
route_aliases' => [
    'poloniex' => 'poloniex_exchange'
]
```
With this alias, we can now use the API to query the `127.0.0.1/api/poloniex/pairs` endpoint instead and regale in the beauty of the URL.

## The end ...?
This is all still very much a work in progress and I'm hoping to add price history for different exchanges. If you were crazy enough to actually read through this scatter-brained documentation and end up creating your own exchange services, feel free to open a PR!

