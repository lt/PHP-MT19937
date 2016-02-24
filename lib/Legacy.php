<?php

namespace MT;

if (PHP_INT_SIZE > 4) {
    class Legacy extends MT
    {
        protected function twist($m, $u, $v)
        {
            $y = ($u & 0x80000000) | ($v & 0x7fffffff);
            return $m ^ ($y >> 1) ^ [0, 0x9908b0df][$u & 1];
        }
    }
}
else {
    class Legacy extends MT
    {
        protected function twist($m, $u, $v)
        {
            $y = [
                $v[0],
                ($u[1] & 0x8000) | $v[1] & 0x7fff
            ];

            $m = [
                $m[0] ^ (($y[0] >> 1) | ($y[1] << 15) & 0xffff),
                $m[1] ^ ($y[1] >> 1)
            ];

            if ($u[0] & 1) {
                $m[0] ^= 0xb0df;
                $m[1] ^= 0x9908;
            }
            else {
                $m[0] ^= 0;
                $m[1] ^= 0;
            }

            return $m;
        }
    }
}