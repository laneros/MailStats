<?php

namespace Laneros\MailStats\Reporter;

class Reporter implements \Swift_Plugins_Reporter
{
    public static $cache = array();

    public function notify(\Swift_Mime_SimpleMessage $message, $address, $result)
    {
       if (self::RESULT_FAIL == $result) {
           return;
        }

        $message_id = $message->getHeaders()->get('Message-ID')->getFieldBody();
        if (preg_match('/[a-z0-9]+(?=@)/s', $message_id, $match)) {
            $message_id = $match[0];
        } else {
            return;
        }


        if (!isset(static::$cache[$message_id])) {
            static::$cache[$message_id] = $message;
        }
    }

    public static function enqueueEmailStats() {
        $cache = static::$cache;
        
        \XF::runOnce('msEmailStats', function()
		{
            if (sizeof(static::$cache) == 0) {
                return;
            }

            foreach(static::$cache AS $message) {
                $children = $message->getChildren();

                $messageText = null;
                $messageHtml = null;
        
                $headers = $message->getHeaders()->toString();
                
                foreach($children AS $child) {
                    if (!($child instanceof \Swift_MimePart)) {
                        continue;
                    }
        
                    if ($child->getBodyContentType() == 'text/plain') {
                        $messageText = $child->getBody();
                    }
        
                    if ($child->getBodyContentType() == 'text/html') {
                        $messageHtml = $child->getBody();
                    }
                }
        
                \XF::db()->query(
                            "
                    INSERT INTO xf_ms_stats
                        (date, email, subject, headers, message_text, message_html)
                    VALUES
                        (?, ?, ?, ?, ?, ?)
                ",
                    [\XF::$time, key($message->getTo()), $message->getSubject(), $headers, $messageText, $messageHtml]
                );  
            };
		});
    }
}
