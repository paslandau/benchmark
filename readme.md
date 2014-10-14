#Benchmark

Convenience class for performing benchmark checks on PHP functions.

##Description

I've found myself time and time again writing a small benchmark test for some PHP functions to check for performance implications.
This project simplifies the process by introducing a `Benchmark` class that takes a list of callables as input and performs them `n` times 
while measuring the runtime of each call.

The results are presented as a `BenchmarkResultList` and can be sorted by average, total and median runtime.

##Basic Usage
```php
    use paslandau\Benchmark\Benchmark;
    use paslandau\Benchmark\BenchmarkResultList;

    require_once __DIR__.'/../../vendor/autoload.php';

    //define tests
    $tests = [
        "explode" => function ($params){
            $c = array_pop(explode('\\', get_class($params)));
            return $c;
        },
        "reflection" => function ($params){
            $c = (new \ReflectionClass($params))->getShortName();
            return $c;
        },
        "basename" => function ($params){
            $c = basename(str_replace('\\', '/', get_class($params)));
            return $c;
        },
    ];

    // get 10000 data points
    $runs = 10000;
    // call each test 10 to obtain one data point (default is 1)
    $iterations = 10;

    //run tests
    $benchmark = new Benchmark($tests, $runs, $iterations);
    $res = $benchmark->run($benchmark);

    // order by total time  (default is BenchmarkResultList::Average)
    $res->setOrderBy(BenchmarkResultList::TOTAL);
    // set number of decimal digits to round to (default is 2)
    $res->setPrecision(6);

    // display results
    echo $res;
```
    
**Output**

    reflection:
    ==========
    Total  : 4.541255 s
    Median : 0 s
    Average: 0.000454 s
    
    explode:
    ==========
    Total  : 4.876281 s
    Median : 0 s
    Average: 0.000488 s
    
    basename:
    ==========
    Total  : 5.281304 s
    Median : 0.001 s
    Average: 0.000528 s

##Requirements

- PHP >= 5.5

##Installation

The recommended way to install Benchmark is through [Composer](http://getcomposer.org/).

    curl -sS https://getcomposer.org/installer | php

Next, update your project's composer.json file to include FixEncodingSubscriber:

    {
        "require": {
            "paslandau/Benchmark": "~0"
        }
    }

After installing, you need to require Composer's autoloader:

    require 'vendor/autoload.php';

##Docu

###Defining tests
Each test is defined as a `Closure` that gets the `$params` parameter passed when executed. The tests are then passed to the `Benchmark` as an associative array.

    //define tests
    $tests = [
        "explode" => function ($params){
            $c = array_pop(explode('\\', get_class($params)));
            return $c;
        }
    ];
    
    $benchmark = new Benchmark($tests, 1);
    
###Defining runs and iterations
One iteration is defined as one call to a test. One run is defined as `n` iterations. So the total number of test executions is `runs` times `iterations`.
The distinction is made due to the fact that a test can potentially be executed extremely fast, resulting in a `0` value for the median.

    $tests = ['foo' => function($params){}];
    $runs = 1000;
    $iterations = 100
    $benchmark = new Benchmark($tests, $runs, $iterations); // test 'foo' will be executed 100000 times
    
###Defining output format
The result of a `Benchmark::run` is an object of type `BenchmarkResultList` which implements the `__toString()` method. You can specify the order 
of the displayed results as well as the precision used when displaying the run times in seconds.

    $tests = ['foo' => function($params){}];
    $benchmark = new Benchmark($tests);
    
    $resultList = $benchmark->run();
    
    $order = BenchmarkResultList::MEDIAN; // order results by median time. Other values are BenchmarkResultList::AVERAGE and BenchmarkResultList::TOTAL
    $precision = 5; // use 5 decimal digits to round the result
    
    $res->setOrderBy($order);
    $res->setPrecision($precision);

    // display results
    echo $res;
    
##Related projects

- [php-benchmark](https://github.com/lavoiesl/php-benchmark)