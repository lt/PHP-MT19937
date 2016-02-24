<?php

namespace MT;

if (PHP_INT_SIZE > 4) {
    class MT19937AR extends MT19937AR_64 {}
}
else {
    class MT19937AR extends MT19937AR_32 {}
}