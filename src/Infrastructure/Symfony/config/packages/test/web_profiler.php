<?php

declare(strict_types=1);

use Symfony\Config\Framework\ProfilerConfig;
use Symfony\Config\FrameworkConfig;
use Symfony\Config\WebProfilerConfig;

return static function (WebProfilerConfig $webProfiler, FrameworkConfig $framework): void {
    $webProfiler->toolbar(false)->interceptRedirects(false);

    $profiler = $framework->profiler();
    assert($profiler instanceof ProfilerConfig);
    $profiler->collect(false);
};
