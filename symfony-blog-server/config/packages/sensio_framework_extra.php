<?php

use Symfony\Config\SensioFrameworkExtraConfig;

return static function (SensioFrameworkExtraConfig $framework) {
    $framework->router()->annotations(false);
};
