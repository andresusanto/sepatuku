<?php
class Helper_Mail
{
    static function isStringHtml($body)
    {
        if (strpos($body, '<p') === 0) {
            return TRUE;
        }
        if (strpos($body, '<html') !== FALSE) {
            return TRUE;
        }
        return FALSE;
    }

    static function sendEmailWithFooter($controller, $from, $to, $cc, $bcc, $subject, $body, $headers=array(), $attachments=array())
    {
        if (isset($controller->feature_limiter) and $controller->feature_limiter->isFooterInEmailNeeded()) {
            if (self::isStringHtml($body)) {
                $bodyNoEndHTML = explode('</html>',$body);
                // we only need first entry and it will always exist
                $bodyNoEndHTML[0] .= $controller->getKrcoConfigValue('email_footer');
                $body = implode('</html>',$bodyNoEndHTML);
            } else {
                $body .= $controller->getKrcoConfigValue('email_footer');
            }
        }
        self::sendEmail($controller, $from, $to, $cc, $bcc, $subject, $body, $headers, $attachments);
    }

    static function sendEmail($controller, $from, $to, $cc, $bcc, $subject, $body, $headers=array(), $attachments=array())
    {
        if (!$body) {
            return;
        }
        if (!$to) {
            if (!isset($controller->deployment_config['developer_email'])) {
                return;
            }
            $to = $controller->deployment_config['developer_email'];
            $subject = "[Possible Spam] " . $subject;
        }
        if (self::isStringHtml($body)) {
            $headers['Content-Type'] = 'text/html; charset=utf-8';
            $style = '';
            if (($controller->getKrcoConfigVersion() >= 3) || $controller->getKrcoConfigValue('with_auto_email_style')) {
                $style = <<<EOD
<style>
p {margin-bottom: 1em;}
table {margin-bottom: 1em;}
</style>

EOD;
            }
            $body = $style . $body;
        }
        if (isset($controller->deployment_config['bcc_global'])) {
            $bccPrefix = "";
            if ($bcc) {
                $bccPrefix = $bcc . ", ";
            }
            $bcc = $bccPrefix . $controller->deployment_config['bcc_global'];
        }
        $controller->mailer->send($from, $to, $cc, $bcc, $subject, $body, $headers, $attachments);
    }

    static function sendSms($controller, $to, $body)
    {
        if (isset($controller->smser) && $body) {
            $site_id = '';
            if (isset($controller->deployment_config['site_id'])) {
                $site_id = $controller->deployment_config['site_id'];
            }
            $controller->smser->send($to, $body, $site_id);
        } else {
                    // var_dump($body);
        die('ga jadi kirim');

        }
    }
}
