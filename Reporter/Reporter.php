<?php

namespace Laneros\MailStats\Reporter;

class Reporter implements \Swift_Plugins_Reporter
{

    public function notify(\Swift_Mime_SimpleMessage $message, $address, $result)
    {
        $db = \XF::db();

        $result = self::RESULT_PASS == $result ? 'pass' : 'fail';

        $db->query("
            INSERT INTO xf_ms_stats
                (date, email, subject, report)
            VALUES
                (?, ?, ?, ?)
        ", [\XF::$time, $address, $message->getSubject(), $result]);
    }
}
