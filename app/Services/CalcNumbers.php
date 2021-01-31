<?php

namespace App\Services;

class CalcNumbers
{
    public function calc(int $target): array
    {
        $includePrimeNumbers = $this->getIncludePrimeNumbers($target);
        $factors = $this->getFactors($target);

        $primeFactors = [];
        foreach ($factors as $factor) {
            if (in_array($factor, $includePrimeNumbers)) {
                $primeFactors[] = $factor;
            }
        }

        return [
            'a' => $factors,
            'b' => $includePrimeNumbers,
            'c' => $primeFactors,
        ];
    }

    protected function getFactors(int $target): array
    {
        $r = [];
        for ($i = 1; $i <= $target; $i++) {
            $last = $target % $i;
            if ($last === 0) {
                $r[] = $i;
            }
        }

        return $r;
    }

    protected function getIncludePrimeNumbers(int $target): array
    {
        $r = [];
        for ($i = 2; $i <= $target; $i++) {
            if ($this->isPrimeNumber($i)) {
                $r[] = $i;
            }
        }

        return $r;
    }

    protected function isPrimeNumber(int $n): bool
    {
        $t = $n % 2;
        if ($t === 0) {
            return false;
        }

        for ($i = 3; $i <= round(sqrt($n)); $i++) {
            if ($n%$i === 0) {
                return false;
            }
        }

        return true;
    }
}
