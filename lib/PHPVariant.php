<?php

namespace MersenneTwister;

if (PHP_INT_SIZE > 4) {
    class PHPVariant extends PHPVariant_64 {}
}
else {
    class PHPVariant extends PHPVariant_32 {}
}