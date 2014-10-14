<?php
/**
 * Created by PhpStorm.
 * User: Hirnhamster
 * Date: 14.10.2014
 * Time: 11:46
 */

namespace paslandau\Benchmark;


class BenchmarkResult {

    /**
     * @var float[]
     */
    private $dataPoints;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $precision;

    /**
     * @param string $name
     * @param float[] $dataPoints
     * @param null|int $precision
     */
    function __construct($name, array $dataPoints, $precision = null)
    {
        $this->name = $name;
        $this->dataPoints = $dataPoints;
        $this->precision = $precision;
    }

    /**
     * @return \float[]
     */
    public function getDataPoints()
    {
        return $this->dataPoints;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * @param null|int $precision
     * @return float
     */
    public function getMedian($precision = null){
        $data = $this->dataPoints;
        sort($data);
        $middle = (int)round(count($data) / 2);
        $middle = $middle-1>=0?$middle-1:$middle;
        $median = $data[$middle];
        return $this->formatFloat($median);
    }

    /**
     * @param null|int $precision
     * @return float
     */
    public function getAverage($precision = null){
        $count = count($this->dataPoints);
        $sum = array_sum($this->dataPoints);
        $avg = $sum / $count;
        return $this->formatFloat($avg);
    }

    /**
     * @param null|int $precision
     * @return float
     */
    public function getTotal($precision = null){
        $sum = array_sum($this->dataPoints);
        return $this->formatFloat($sum);
    }

    private function formatFloat($number, $precision = null){
        $precision = $precision!==null?$precision:$this->precision;
        if($precision !== null){
            return round($number,$precision);
        }
        return $number;
    }

    public function __toString(){
        $avg =  $this->getAverage();
        $total = $this->getTotal();
        $median = $this->getMedian();
        $s = [];
        //evaluate
        $s[] = "{$this->name}:\n==========";
        $s[] = "Total  : $total s";
        $s[] = "Median : $median s";
        $s[] = "Average: $avg s\n";

        return implode("\n",$s);
    }
} 