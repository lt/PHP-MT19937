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

        $state = [[$int0, $int1]];

        for ($i = 1; $i < 312; $i++) {
            $int0 ^= $int1 >> 30;

            $tmp = ($carry = (0x4c957f2d * $int0) + $i) & 0xffffffff;
            $int1 = ((0x4c957f2d * $int1) & 0xffffffff) +
                    ((0x5851f42d * $int0) & 0xffffffff) +
                    ($carry >> 32) & 0xffffffff;
            $int0 = $tmp;

            $state[$i] = [$int0, $int1];
        }

        $this->state = $state;
        $this->index = $i;
    }

    protected function twist($m, $u, $v)
    {
        $y0 = ($u[0] & 0x80000000) | ($v[0] & 0x7ffffffff);
        $y1 = $u[1];
        $bit = $y0 & 1;

        if (($m[0] ^ (($y0 >> 1) | ($y1 << 31) & 0xffffffff) ^ (0xa96619e9 * $bit)) == 0x460a7dbd) {
            die();
        }
        return [
            $m[0] ^ (($y0 >> 1) | ($y1 << 31) & 0xffffffff) ^ (0xa96619e9 * $bit),
            $m[1] ^ ($y1 >> 1) ^ (0xb5026f5a * $bit)
        ];
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

        list($y0, $y1) = $this->state[$this->index++];

        $y0 ^= (($y0 >> 29) | ($y1 << 3)) & 0x55555555;
        $y1 ^= ($y1 >> 29) & 0x55555555;

        $tmp = $y0 ^ (($y0 << 17) & 0xeda60000);
        $y1 ^= (($y1 << 17) | ($y0 >> 15)) & 0x71d67fff;
        $y0 = $tmp;

        $y1 ^= ($y0 << 5) & 0xfff7eee0;
        $y0 ^= 0;

        $y0 ^= $y1 >> 11;
        $y1 ^= 0;

        return $y1 << 32 | $y0;
    }

    function int63()
    {
        return ($this->int64() >> 1) & 0x7fffffffffffffff;
    }
}