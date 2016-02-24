<?php

namespace MT;

if (PHP_INT_SIZE > 4) {
    class MT
    {
        private $state = [];
        private $index = 625;

        function init($seed)
        {
            $state = [$seed & 0xffffffff];

            for ($i = 1; $i < 624; $i++) {
                $state[$i] = (1812433253 * ($state[$i - 1] ^ ($state[$i - 1] >> 30)) + $i) & 0xffffffff;
            }

            $this->state = $state;
            $this->index = $i;
        }

        protected function twist($m, $u, $v)
        {
            $y = ($u & 0x80000000) | ($v & 0x7fffffff);
            return $m ^ ($y >> 1) ^ [0, 0x9908b0df][$y & 1];
        }

        private function int32()
        {
            if ($this->index >= 624) {
                if ($this->index === 625) {
                    $this->init(5489);
                }

                for ($i = 0; $i < 227; $i++) {
                    $this->state[$i] = $this->twist($this->state[$i + 397], $this->state[$i], $this->state[$i + 1]);
                }
                for (; $i < 623; $i++) {
                    $this->state[$i] = $this->twist($this->state[$i - 227], $this->state[$i], $this->state[$i + 1]);
                }
                $this->state[623] = $this->twist($this->state[396], $this->state[623], $this->state[0]);

                $this->index = 0;
            }

            $y = $this->state[$this->index++];

            $y ^= ($y >> 11);
            $y ^= ($y << 7) & 0x9d2c5680;
            $y ^= ($y << 15) & 0xefc60000;
            $y ^= ($y >> 18);

            return $y;
        }

        function int31()
        {
            return $this->int32() >> 1;
        }
    }
}
else {
    class MT
    {
        private $state = [];
        private $index = 625;

        function init($seed)
        {
            $int0 = $seed & 0xffff;
            $int1 = ($seed >> 16) & 0xffff;

            $state = [[$int0, $int1]];

            for ($i = 1; $i < 624; $i++) {
                $int0 ^= $int1 >> 14;

                $tmp = ($carry = (0x8965 * $int0) + $i) & 0xffff;
                $int1 = (0x8965 * $int1) + (0x6C07 * $int0) + ($carry >> 16) & 0xffff;
                $int0 = $tmp;

                $state[$i] = [$int0, $int1];
            }

            $this->state = $state;
            $this->index = $i;
        }

        protected function twist($m, $u, $v)
        {
            $y = [
                $v[0],
                ($u[1] & 0x8000) | $v[1] & 0x7fff
            ];

            $m = [
                $m[0] ^ (($y[0] >> 1) | ($y[1] << 15) & 0xffff),
                $m[1] ^ ($y[1] >> 1)
            ];

            if ($y[0] & 1) {
                $m[0] ^= 0xb0df;
                $m[1] ^= 0x9908;
            }
            else {
                $m[0] ^= 0;
                $m[1] ^= 0;
            }

            return $m;
        }

        private function int32()
        {
            if ($this->index >= 624) {
                if ($this->index === 625) {
                    $this->init(5489);
                }

                for ($i = 0; $i < 227; $i++) {
                    $this->state[$i] = $this->twist($this->state[$i + 397], $this->state[$i], $this->state[$i + 1]);
                }
                for (; $i < 623; $i++) {
                    $this->state[$i] = $this->twist($this->state[$i - 227], $this->state[$i], $this->state[$i + 1]);
                }
                $this->state[623] = $this->twist($this->state[396], $this->state[623], $this->state[0]);

                $this->index = 0;
            }

            $y = $this->state[$this->index++];

            $y[0] ^= ($y[0] >> 11) | (($y[1] << 5) & 0xffff);
            $y[1] ^= $y[1] >> 11;

            $tmp = $y[0] ^ (($y[0] << 7) & 0x5680);
            $y[1] ^= (($y[1] << 7) | ($y[0] >> 9)) & 0x9d2c;
            $y[0] = $tmp;

            $tmp = $y[0] ^ 0;
            $y[1] ^= (($y[1] << 15) | ($y[0] >> 1)) & 0xefc6;
            $y[0] = $tmp;

            $y[0] ^= $y[1] >> 2;
            $y[1] ^= 0;

            return $y;
        }

        function int31()
        {
            $int32 = $this->int32();
            return ($int32[1] << 15) | ($int32[0] >> 1);
        }
    }
}