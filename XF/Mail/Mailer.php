<?php

namespace Laneros\MailStats\XF\Mail;

class Mailer extends XFCP_Mailer
{
    public function send(\Swift_Mime_SimpleMessage $message, \Swift_Transport $transport = null, array $queueEntry = null, $allowRetry = true)
    {
        if (!$transport) {
            $transport = $this->defaultTransport;
        }

        $transport->registerPlugin(new \Swift_Plugins_ReporterPlugin(new \Laneros\MailStats\Reporter\Reporter()));

        $result = parent::send($message, $transport, $queueEntry, $allowRetry);

        return $result;
    }
}
