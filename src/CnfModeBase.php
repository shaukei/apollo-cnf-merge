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

    abstract public function encodeCnf(array $cnfs);

    abstract public function mergeCnf();

    abstract public function putCnf();

    abstract public function loadOrgCnf();

    public function __construct(array $cnfs = [], $path = './', $file = 'default.php')
    {
        $this->loadCnfs($cnfs);
        $this->filename = $file;
        $this->path = $path;
    }

    public function update()
    {
        $cnfs = $this->loadOrgCnf()->mergeCnf();
        return $this->encodeCnf($cnfs)->putCnf();
    }

    public function loadCnfs(array $cnfs = [])
    {
        if (empty($cnfs)) return null;
        $this->cnfs = $cnfs;
    }
}
