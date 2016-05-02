Mersenne Twister written in PHP
===============================

This library contains pure PHP implementations of the Mersenne Twister pseudo-random number generation algorithm.

There are 3 classes available

### `MersenneTwister\MT`

This is the Mersenne Twister algorithm as defined by the algorithm authors.

It works on both 32 and 64 bit builds of PHP and outputs 32 bit integers.

```php
$mt = new \MersenneTwister\MT();
$mt->init(1234); // mt_srand(1234);
$mt->int31();    // int31() per mt19937ar.c, positive values only
$mt->int32();    // int32() per mt19937ar.c, high bit sets sign
```

### `MersenneTwister\MT64`

This is the 64-bit Mersenne Twister algorithm as defined by the algorithm authors.

It works **only on 64 bit builds of PHP** and outputs 64 bit integers.

```php
$mt = new \MersenneTwister\MT64();
$mt->init(1234);
$mt->int63();    // int63() per mt19937-64.c, positive values only
$mt->int64();    // int64() per mt19937-64.c, high bit sets sign
```

### `MersenneTwister\PHPVariant`

This is the Mersenne Twister algorithm as defined in PHP 5.2.1+. It is slightly different from the original algorithm and outputs a different set of numbers

It works on both 32 and 64 bit builds of PHP and outputs 32 bit integers.

```php
$mt = new \MersenneTwister\MT();
$mt->init(1234);     // mt_srand(1234);
$mt->int31();        // mt_rand();

// Breaks on huge ranges (i.e. PHP_INT_MIN, PHP_INT_MAX)
// PHP also breaks on huge ranges, but breaks differently.
$mt->rand(min, max); // mt_rand(min, max);
```
