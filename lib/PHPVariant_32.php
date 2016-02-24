<?php

namespace MersenneTwister;

abstract class PHPVariant_32 extends MT_32
{
    protected function twist($m, $u, $v)
    {
        $y1 = ($u[1] & 0x8000) | $v[1] & 0x7fff;
        $bit = $u[0] & 1;

        return [
            $m[0] ^ (($v[0] >> 1) | ($y1 << 15) & 0xffff) ^ (0xb0df * $bit),
            $m[1] ^ ($y1 >> 1) ^ (0x9908 * $bit),
        ];
    }
}