<?php

namespace App\Services\Channels;

class MockWhatsAppChannelAdapter extends MockMetaChannelAdapter
{
    public function __construct()
    {
        parent::__construct('whatsapp');
    }
}
