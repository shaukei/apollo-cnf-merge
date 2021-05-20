<?php

namespace Freya\ApolloCnf\CnfMode;

use Freya\ApolloCnf\CnfModeBase;

class JsonFile extends CnfModeBase
{
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
        if (is_file($this->path . '/' . $this->filename)) {
            file_put_contents($this->path . '/' . $this->filename, $this->encode);
        }
        return $this->encode;
    }

    public function loadOrgCnf()
    {
        if (is_file($this->path . '/' . $this->filename)) {
            $this->orgCnf = json_decode(file_get_contents($this->path . '/' . $this->filename), true);
        }
        return $this;
    }
}