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
        if ($NSFile) {
            $this->filename = $path . '/' . $appid . '/' . $namespaceName . '.' . $this->fileExt;
            $fullpath = dirname($this->filename);
            if (!is_dir($fullpath)) {
                mkdir($fullpath, 0755, true);
            }
        } else {
            $this->filename = $path . '/' . $file;
        }
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
