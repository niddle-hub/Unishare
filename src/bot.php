<?php

require 'SiteParser.php';
date_default_timezone_set('Asia/Yekaterinburg');
$SiteParser = new SiteParser();
echo $SiteParser->getSchedule('исит-1701','now');
