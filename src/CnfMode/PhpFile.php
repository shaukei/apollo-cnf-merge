<?php

namespace Freya\ApolloCnf\CnfMode;

use Freya\ApolloCnf\CnfModeBase;

class PhpFile extends CnfModeBase
{
    protected $fileExt = 'php';

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
        file_put_contents($this->path . '/' . $this->filename, $this->encode);
        return $this->encode;
    }

    public function loadOrgCnf()
    {
        if (is_file($this->path . '/' . $this->filename)) {
            $this->orgCnf = include $this->path . '/' . $this->filename;
        } else {
            $this->orgCnf = [];
        }
        return $this;
    }
}