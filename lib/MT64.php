<?php declare(strict_types = 1);

namespace MersenneTwister;

class MT64
{
    private $state = [];
    private $index = 313;

    function init($seed)
    {
        $int0 = $seed & 0xffffffff;
        $int1 = ($seed >> 32) & 0xffffffff;

        $state = [$seed];

        for ($i = 1; $i < 312; $i++) {
            // This is a 64-bit safe version of:
            // $state[$i] = (6364136223846793005 * ($state[$i - 1] ^ ($state[$i - 1] >> 62)) + $i);
            $int0 ^= $int1 >> 30;

            $tmp = ($carry = (0x4c957f2d * $int0) + $i) & 0xffffffff;
            $int1 = ((0x4c957f2d * $int1) & 0xffffffff) +
                    ((0x5851f42d * $int0) & 0xffffffff) +
                    ($carry >> 32) & 0xffffffff;
            $int0 = $tmp;

            $state[$i] = ($int1 << 32) | $int0;
        }

        $this->state = $state;
        $this->index = $i;
    }

    protected function twist($m, $u, $v)
    {
        $y = ($u & -2147483648) | ($v & 0x7fffffff);
        return $m ^ (($y >> 1) & 0x7fffffffffffffff) ^ (-5403634167711393303 * ($v & 1));
    }

    function int64()
    {
        if ($this->index >= 312) {
            if ($this->index === 313) {
                $this->init(5489);
            }

            $state = $this->state;
            for ($i = 0; $i < 156; $i++) {
                $state[$i] = $this->twist($state[$i + 156], $state[$i], $state[$i + 1]);
            }
            for (; $i < 311; $i++) {
                $state[$i] = $this->twist($state[$i - 156], $state[$i], $state[$i + 1]);
            }
            $state[311] = $this->twist($state[155], $state[311], $state[0]);
            $this->state = $state;

            $this->index = 0;
        }

        $y = $this->state[$this->index++];

        $y ^= ($y >> 29) & 0x0000000555555555;
        $y ^= ($y << 17) & 0x71d67fffeda60000;
        $y ^= ($y << 37) &  -2270628950310912;
        $y ^= ($y >> 43) & 0x00000000001fffff;

        return $y;
    }

    function int63()
    {
        return ($this->int64() >> 1) & 0x7fffffffffffffff;
    }
}