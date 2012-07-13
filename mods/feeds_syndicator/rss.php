<?php

// interface for feeds.php

$format = 'rss';

$phpbb_root_path = './';

define ('IN_PHPBB', true);
include($phpbb_root_path . 'extension.inc');

include('feeds.'.$phpEx);

?>
