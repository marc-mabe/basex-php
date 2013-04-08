<?php

namespace basex;

class BinaryConverter
{
    const IGNORE_MISSING_PAD =  1;
    const IGNORE_WHITESPACE  =  3;
    const IGNORE_UNKNOWN     =  7;
    const CASE_INSENSITIVE   = 15;

    private static $pow21Table = array(
        0 =>   0,
        1 =>   1,
        2 =>   3,
        3 =>   7,
        4 =>  15,
        5 =>  31,
        6 =>  63,
        7 => 127,
        8 => 255
    );

    private static $chunkBitSize = array(
        // 1 =>  8,
        // 2 =>  8,
        3 => 24,
        // 4 =>  8,
        5 => 40,
        6 => 24,
        7 => 56,
        // 8 =>  8
    );

    private static $baseAndLog2Map = array(
          2 => 1,
          4 => 2,
          8 => 3,
         16 => 4,
         32 => 5,
         64 => 6,
        128 => 7,
        256 => 8,
    );

    private $base;
    private $bitSize;
    private $alphabet;
    private $pad;

    public function __construct($base, $alphabet, $pad = '')
    {
        $this->setBase($base);
        $this->setAlphabet($alphabet);
        $this->setPad($pad);
    }

    public function setAlphabet($alphabet)
    {
        // TODO: validate $alphabet
        $alphabet = (string)$alphabet;
        $this->alphabet = $alphabet;
    }

    public function getAlphabet()
    {
        return $this->alphabet;
    }

    public function setPad($pad)
    {
        // TODO: validate $pad
        $pad = (string) $pad;
        $this->pad = $pad;
    }

    public function getPad()
    {
        return $this->pad;
    }

    public function setBase($base)
    {
        if (!isset(self::$baseAndLog2Map[$base])) {
            throw new InvalidArgumentException(sprintf(
                "Invalid base of '%s' given, have to be one of %s",
                $base,
                implode(', ', array_keys(self::$baseAndLog2Map))
            ));
        }

        $this->base    = (int) $base;
        $this->bitSize = self::$baseAndLog2Map[$base];
    }

    public function getBase()
    {
        return $this->base;
    }

    public function encode($bin)
    {
        $binLen = strlen($bin);
        $result = '';
        $ord    = $ordSize = 0;
        for($i = 0; $i < $binLen; $i++) {
            $ord     = ($ord << 8) + ord($bin[$i]);
            $ordSize = $ordSize + 8;

            while ($ordSize >= $this->bitSize) {
                $result  .= $this->alphabet[$ord >> ($ordSize - $this->bitSize)];
                $ordSize -= $this->bitSize;
                $ord      = $ord & self::$pow21Table[$ordSize];
            }
        }

        if ($ordSize > 0) {
            $result .= $this->alphabet[$ord << ($this->bitSize - $ordSize)];

            if ($this->pad !== '') {
                $chunkSize = self::$chunkBitSize[$this->bitSize] / $this->bitSize;
                $padLen    = $chunkSize - (strlen($result) % $chunkSize);
                $result   .= str_repeat($this->pad, $padLen);
            }
        }

        return $result;
    }

    public function decode($str, $strictness = 0)
    {}
}

