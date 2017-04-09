<?

// Settings
$scheme = 'fakegpsgo';
$ios_id = 1234567;
$android_package = 'com.gsmartstudio.fakegps';
$auto = false;

// No trailing slash after path, conform to http://x-callback-url.com/specifications/
$REQUEST_URI = preg_replace('@/(?:\?|$)@', '', $_SERVER['REQUEST_URI']);

// Detection
$HTTP_USER_AGENT = strtolower($_SERVER['HTTP_USER_AGENT']);
$android = (bool) strpos($HTTP_USER_AGENT, 'android');
$iphone = !$android && ((bool) strpos($HTTP_USER_AGENT, 'iphone') || (bool) strpos($HTTP_USER_AGENT, 'ipod'));
$ipad = !$android && !$iphone && (bool) strpos($HTTP_USER_AGENT, 'ipad');
$ios = $iphone || $ipad;
$mobile = $android || $ios;

// Install
$android_install = 'http://play.google.com/store/apps/details?id=' . $android_package;

// Open
if ($ios) {
    $open = $scheme . ':/' . $REQUEST_URI;
}
if ($android) {
    $open = 'intent:/' . $REQUEST_URI . '#Intent;package=' . $android_package . ';scheme=' . $scheme . ';launchFlags=268435456;end;';
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>URL Schemes</title>
        <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <? if ($ios): ?>
            <meta name="apple-itunes-app" content="app-id=<?= $ios_id ?>, app-argument=<?= $open ?>"/>
        <? endif ?>
    </head>
    <body>

        <script>
        function open() {
            window.location = '<?= $open ?>';

            <? if ($ios): ?>
                setTimeout(function() {
                    if (!document.webkitHidden) {
                        window.location = '<?= $ios_install ?>';
                    }
                }, 25);
            <? endif ?>
        }
        </script>

        <? if ($mobile): ?>
    
            <? if ($ios): ?>
                <p>Click the banner on top of this screen to <a href="<?= $ios_install ?>">install</a> our app or directly <a href="<?= $open ?>">open</a> this content in our app if you have it installed already.</p>
    
            <? elseif ($android): ?>
                <p>Go ahead and <a href="<?= $android_install ?>">install</a> our app or directly <a href="<?= $open ?>">open</a> this content in our app if you have it installed already.<p>
            <? endif ?>

            <? if ($auto): ?>
                <script>open();</script>
            <? endif ?>

        <? else: ?>
            <p>Go to the <a href="<?= $ios_install ?>">App Store</a> or <a href="<?= $android_install ?>">Google Play</a> to install and open this content in our app.</p>
        <? endif ?>

    </body>
</html>