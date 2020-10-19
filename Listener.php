<?php

namespace Laneros\MailStats;

class Listener
{
    public static function app_setup(\XF\App $app)
    {
        \Laneros\MailStats\Reporter\Reporter::enqueueEmailStats();
    }
}
