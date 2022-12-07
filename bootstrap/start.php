<?php

use MkyCore\Application;

define('ROOT_APP', trim(str_replace('public', '', getcwd()), '/\\'));

$app = new Application(ROOT_APP);

return $app;