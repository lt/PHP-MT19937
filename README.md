Mersenne Twister written in PHP
===============================

This library contains pure PHP implementations of the Mersenne Twister pseudo-random number generation algorithm.

There are 3 classes available

### `MersenneTwister\MT`

This is the Mersenne Twister algorithm as defined by the algorithm authors.

It works on both 32 and 64 bit builds of PHP and outputs 32 bit integers.

### `MersenneTwister\MT64`

This is the 64-bit Mersenne Twister algorithm as defined by the algorithm authors.

It works **only on 64 bit builds of PHP** and outputs 64 bit integers.

### `MersenneTwister\PHPVariant`

This is the Mersenne Twister algorithm as defined in PHP 5.2.1+. It is slightly different from the original algorithm and outputs a different set of numbers

It works on both 32 and 64 bit builds of PHP and outputs 32 bit integers.
