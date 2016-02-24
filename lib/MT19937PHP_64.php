<?php

namespace MT;

abstract class MT19937PHP_64 extends MT19937AR_64
{
    protected function twist($m, $u, $v)
    {
        $y = ($u & 0x80000000) | ($v & 0x7fffffff);
        return $m ^ ($y >> 1) ^ [0, 0x9908b0df][$u & 1];
    }
}