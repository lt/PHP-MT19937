<?php declare(strict_types = 1);

namespace MT;

interface PRNG
{
    function init($seed);
    function int31();
}