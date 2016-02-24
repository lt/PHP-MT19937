<?php

namespace MT;

abstract class MT19937PHP_32 extends MT19937AR_32
{
    protected function twist($m, $u, $v)
    {
        $y1 = ($u[1] & 0x8000) | $v[1] & 0x7fff;

        list($x0, $x1) = [[0, 0], [0xb0df, 0x9908]][$u[0] & 1];

        return [
            $m[0] ^ (($v[0] >> 1) | ($y1 << 15) & 0xffff) ^ $x0,
            $m[1] ^ ($y1 >> 1) ^ $x1
        ];
    }
}