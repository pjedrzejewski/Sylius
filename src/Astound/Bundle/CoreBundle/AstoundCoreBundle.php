<?php

/*
 *  Override of Sylius bundle by the same name
 */

namespace Astound\Bundle\CoreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Astound core bundle.
 */
class AstoundCoreBundle extends Bundle
{
    public function getParent()
    {   
        return 'SyliusCoreBundle';
    }
}
