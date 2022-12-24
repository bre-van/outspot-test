<?php
function getPaymentUrl($hash): string
{
    return getCurrentUrlPath() . 'payment.php?hash=' . $hash;
}

function getPaymentHash(): string
{
    return sha1(SALT . session_id());
}

function getCurrentUrlPath(): string
{
    $url = "http" . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 's' : '') . "://";
    $url .= $_SERVER['HTTP_HOST'];
    $url_parts = explode("/", $_SERVER['REQUEST_URI']);

    if (!empty($url_parts)) {
        for ($i = 0; $i < (count($url_parts) - 1); $i++) {
            $url .= $url_parts[$i] . '/';
        }
    }

    return $url;
}