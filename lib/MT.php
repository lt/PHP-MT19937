<?php

namespace MersenneTwister;

if (PHP_INT_SIZE > 4) {
    class MT extends MT_64 {}
}
else {
    class MT extends MT_32 {}
}