<?php
/**
 * Created by PhpStorm.
 * User: Hirnhamster
 * Date: 14.10.2014
 * Time: 12:15
 */

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