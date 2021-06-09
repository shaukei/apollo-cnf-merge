<?php

namespace Freya\ApolloCnf;

class ParseCnf
{
    // 配置的数据
    private $cnfs = [];

    public function __construct(array $cnfs = [])
    {
        $this->setCnfs($cnfs);
    }

    public function parse()
    {
        if (empty($this->cnfs)) {
            return null;
        }

        $cnfs = [];
        foreach($this->cnfs as $key => $val) {
            $cnfs = array_merge_recursive($cnfs, $this->parseKey($key, $val));
        }
        return $cnfs;
    }

    public function setCnfs(array $cnfs)
    {
        $this->cnfs = $cnfs;
        return $this;
    }

    public function getCnfs()
    {
        return $this->cnfs;
    }

    private function parseKey($keyName, $value)
    {
        foreach(array_reverse(explode('.', $keyName)) as $key) {
            $value = [$key => $value];
        }
        return $value;
    }
}
