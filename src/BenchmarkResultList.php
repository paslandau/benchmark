<?php
/**
 * Created by PhpStorm.
 * User: Hirnhamster
 * Date: 14.10.2014
 * Time: 12:05
 */

namespace paslandau\Benchmark;


class BenchmarkResultList {
    const MEDIAN = "Median";
    const AVERAGE = "Average";
    const TOTAL = "Total";

    /**
     * @var BenchmarkResult[]
     */
    private $results;

    /**
     * @var string
     */
    private $orderBy;

    /**
     * @var int
     */
    private $precision;

    /**
     * @param BenchmarkResult[] $results
     * @param string $orderBy [optional]. Default: BenchmarkResultList::MEDIAN.
     * @param int $precision
     */
    function __construct(array $results, $orderBy = null, $precision = null)
    {
        if($orderBy === null){
            $orderBy = self::AVERAGE;
        }
        $this->orderBy = $orderBy;
        if($precision === null){
            $precision = 2;
        }
        $this->precision = $precision;
        $this->results = $results;
    }

    /**
     * @return string
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * @param string $orderBy
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
    }

    /**
     * @return int
     */
    public function getPrecision()
    {
        return $this->precision;
    }

    /**
     * @param int $precision
     */
    public function setPrecision($precision)
    {
        $this->precision = $precision;
    }

    /**
     * @return BenchmarkResult[]
     */
    private function getSortedResults(){
        $times = [];
        foreach($this->results as $name => $res){
            $times[$name] = call_user_func([$res,"get".$this->orderBy]);
        }
        // sort ASC
        asort($times);
        // bring $this->results in order
        return array_replace($times,$this->results);
    }

    public function __toString(){
        $results = $this->getSortedResults();
        $s = [];
        foreach($results as $result){
            $result->setPrecision($this->precision);
            $s[] = $result->__toString();
        }
        return implode("\n",$s);
    }
} 