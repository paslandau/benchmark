<?php
/**
 * Created by PhpStorm.
 * User: Hirnhamster
 * Date: 14.10.2014
 * Time: 11:40
 */
namespace paslandau\Benchmark;

class Benchmark {
    /**
     * @var callable[]
     */
    private $tests;
    /**
     * @var int
     */
    private $runs;
    /**
     * @var int
     */
    private $iterations;

    function __construct($tests, $runs, $iterations = null)
    {
        $this->runs = $runs;
        $this->tests = $tests;
        if($iterations === null){
            $iterations = 1;
        }
        $this->iterations = $iterations;
    }

    /**
     * @param $params
     * @return BenchmarkResultList
     */
    public function run($params){
        $results = [];
        foreach($this->tests as $name => $f) {
            $res = [];
            for($i = 0; $i < $this->runs; $i++) {
                //start
                $start = microtime(true);
                for($j = 0; $j < $this->iterations; $j++) {
                    $f($params);
                }
                $end = microtime(true);
                $res[] = $end - $start;
            }
            $result = new BenchmarkResult($name,$res,2);
            $results[$name] = $result;
        }
        $resultList = new BenchmarkResultList($results);
        return $resultList;
    }
} 