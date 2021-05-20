<?php

namespace Freya\ApolloCnf\CnfMode;

use Freya\ApolloCnf\CnfModeBase;

class PhpFile extends CnfModeBase
{
    public function encodeCnf(array $cnfs)
    {
        $this->encode = '<?php return ' . var_export($cnfs, true) . ';';
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
            $this->orgCnf = include $this->path . '/' . $this->filename;
        }
        return $this;
    }
}