<?php

namespace MersenneTwister;

abstract class PHPVariant_64 extends MT_64
{
    protected function twist($m, $u, $v)
    {
        $y = ($u & 0x80000000) | ($v & 0x7fffffff);
        return $m ^ ($y >> 1) ^ (0x9908b0df * ($u & 1));
    }
}