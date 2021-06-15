<?php

namespace Freya\ApolloCnf;

use Freya\ApolloCnf\CnfModeInterface;

abstract class CnfModeBase implements CnfModeInterface
{
    protected $cnfs = [];

    protected $path = './';

    protected $encode = '';

    protected $orgCnf = null;

    protected $filename = '';

    protected $fileExt = '';

    protected $NSFile = false;

    protected $namespaceName = '';

    abstract public function encodeCnf(array $cnfs);

    abstract public function mergeCnf();

    abstract public function putCnf();

    abstract public function loadOrgCnf();

    public function __construct(array $cnfs = [], $path = './', $file = 'default.php', $NSFile = false, $appid, $namespaceName)
    {
        $this->loadCnfs($cnfs);
        $this->path = $path;
        $this->namespaceName = $namespaceName;
        $this->filename = $NSFile ? $appid . '/' . $namespaceName . '.' . $this->fileExt : $file;
    }

    public function update()
    {
        $cnfs = $this->loadOrgCnf()->mergeCnf();
        return $this->encodeCnf($cnfs)->putCnf();
    }

    public function loadCnfs(array $cnfs = [])
    {
        if (empty($cnfs)) {
            return null;
        }
        $this->cnfs = $cnfs;
    }
}
