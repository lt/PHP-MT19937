<?php

namespace MersenneTwister;

abstract class MT_32
{
    private $state = [];
    private $index = 625;

    function init($seed)
    {
        $int0 = $seed & 0xffff;
        $int1 = ($seed >> 16) & 0xffff;

        $state = [[$int0, $int1]];

        for ($i = 1; $i < 624; $i++) {
            $int0 ^= $int1 >> 14;

            $tmp = ($carry = (0x8965 * $int0) + $i) & 0xffff;
            $int1 = (0x8965 * $int1) + (0x6C07 * $int0) + ($carry >> 16) & 0xffff;
            $int0 = $tmp;

            $state[$i] = [$int0, $int1];
        }

        $this->state = $state;
        $this->index = $i;
    }

    protected function twist($m, $u, $v)
    {
        $y0 = $v[0];
        $y1 = ($u[1] & 0x8000) | $v[1] & 0x7fff;

        list($x0, $x1) = [[0, 0], [0xb0df, 0x9908]][$y0 & 1];

        return [
            $m[0] ^ (($y0 >> 1) | ($y1 << 15) & 0xffff) ^ $x0,
            $m[1] ^ ($y1 >> 1) ^ $x1
        ];
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

        list($y0, $y1) = $this->state[$this->index++];

        $y0 ^= ($y0 >> 11) | (($y1 << 5) & 0xffff);
        $y1 ^= $y1 >> 11;

        $tmp = $y0 ^ (($y0 << 7) & 0x5680);
        $y1 ^= (($y1 << 7) | ($y0 >> 9)) & 0x9d2c;
        $y0 = $tmp;

        $y1 ^= (($y1 << 15) | ($y0 >> 1)) & 0xefc6;
        $y0 ^= 0;

        $y0 ^= $y1 >> 2;
        $y1 ^= 0;

        return $y1 << 16 | $y0;
    }

    function int31()
    {
        return ($this->int32() >> 1) & 0x7fffffff;
    }
}