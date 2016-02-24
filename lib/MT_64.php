<?php

namespace MersenneTwister;

abstract class MT_64
{
    private $state = [];
    private $index = 625;

    function init($seed)
    {
        $state = [$seed & 0xffffffff];

        for ($i = 1; $i < 624; $i++) {
            $state[$i] = (1812433253 * ($state[$i - 1] ^ ($state[$i - 1] >> 30)) + $i) & 0xffffffff;
        }

        $this->state = $state;
        $this->index = $i;
    }

    protected function twist($m, $u, $v)
    {
        $y = ($u & 0x80000000) | ($v & 0x7fffffff);
        return $m ^ ($y >> 1) ^ [0, 0x9908b0df][$y & 1];
    }

    function int32()
    {
        if ($this->index >= 624) {
            if ($this->index === 625) {
                $this->init(5489);
            }

            $state = $this->state;
            for ($i = 0; $i < 227; $i++) {
                $state[$i] = $this->twist($state[$i + 397], $state[$i], $state[$i + 1]);
            }
            for (; $i < 623; $i++) {
                $state[$i] = $this->twist($state[$i - 227], $state[$i], $state[$i + 1]);
            }
            $state[623] = $this->twist($state[396], $state[623], $state[0]);
            $this->state = $state;

            $this->index = 0;
        }

        $y = $this->state[$this->index++];

        $y ^= ($y >> 11);
        $y ^= ($y << 7) & 0x9d2c5680;
        $y ^= ($y << 15) & 0xefc60000;
        $y ^= ($y >> 18);

        return $y;
    }

    function int31()
    {
        return $this->int32() >> 1;
    }
}