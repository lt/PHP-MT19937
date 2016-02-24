<?php

namespace MT;

if (PHP_INT_SIZE > 4) {
    class MT19937PHP extends MT19937PHP_64 {}
}
else {
    class MT19937PHP extends MT19937PHP_32 {}
}