<?php

namespace Lib;

class PageOffsetAndLimit {
    public int $offset;
    public int $limit;

    public function __construct(int $offset, int $limit)
    {
        $this->offset = $offset;
        $this->limit = $limit;
    }
}