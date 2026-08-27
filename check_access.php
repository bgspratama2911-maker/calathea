<?php
$lines = file('C:/xampp/apache/logs/access.log');
$lastLines = array_slice($lines, -25);
echo implode("", $lastLines);
