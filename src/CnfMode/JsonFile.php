<?php

namespace Freya\ApolloCnf\CnfMode;

use Freya\ApolloCnf\CnfModeBase;

class JsonFile extends CnfModeBase
{
    protected $fileExt = 'json';

    public function encodeCnf(array $cnfs)
    {
        $this->encode = json_encode($cnfs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        return $this;
    }

    public function mergeCnf()
    {
        return array_merge((array)$this->orgCnf, $this->cnfs);
    }

    public function putCnf()
    {
        file_put_contents($this->path . '/' . $this->filename, $this->encode);
        return $this->encode;
    }

    public function loadOrgCnf()
    {
        if (is_file($this->path . '/' . $this->filename)) {
            $this->orgCnf = json_decode(file_get_contents($this->path . '/' . $this->filename), true);
        } else {
            $this->orgCnf = [];
        }
        return $this;
    }
}