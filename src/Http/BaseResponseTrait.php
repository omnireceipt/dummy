<?php

namespace Omnireceipt\Dummy\Http;

trait BaseResponseTrait
{
    public function getPayload(): ?array
    {
        return json_decode($this->getData(), true);
    }
}
