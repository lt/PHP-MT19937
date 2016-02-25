<?php

namespace MersenneTwister;

class PHPVariant extends MT
{
    protected function twist($m, $u, $v)
    {
        $y = ($u & 0x80000000) | ($v & 0x7fffffff);
        return $m ^ (($y >> 1) & 0x7fffffff) ^ (0x9908b0df * ($u & 1));
    }
}