<?php

/// initialize some links
$home_link = encodeUrl($this->properties['home.url']);
$update_link = encodeUrl($script.'?task=update_doupdate');

echo "<h2 style=\"text-align: center;\">Account Updater Version $new_version</h2>";
echo $update_logs;
echo '<h3>Finish updating all accounts ...</h3>';