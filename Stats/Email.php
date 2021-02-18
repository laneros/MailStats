<?php

namespace Laneros\MailStats\Stats;

use XF\Stats\AbstractHandler;

class Email extends AbstractHandler
{

    public function getStatsTypes()
    {
        return [
            'email' => \XF::phrase('ms_emails_sent')
        ];
    }

    public function getData($start, $end)
    {
        $db = $this->db();

        $emails = $db->fetchPairs(
            $this->getBasicDataQuery('xf_ms_stats', 'date'),
            [$start, $end]
        );

        return [
            'email' => $emails
        ];
    }
}
