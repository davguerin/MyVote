<?php

namespace DG\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class DGUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
