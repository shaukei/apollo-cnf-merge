<?php

namespace Freya\ApolloCnf;


interface CnfModeInterface
{
    public function loadCnfs(array $cnfs = []);

    public function update();
}
