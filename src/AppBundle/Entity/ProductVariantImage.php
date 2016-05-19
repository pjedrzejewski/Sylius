<?php

namespace AppBundle\Entity;

use Sylius\Component\Core\Model\ProductVariantImage as BaseProductVariantImage;

class ProductVariantImage extends BaseProductVariantImage
{
    private $position;

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }
}
