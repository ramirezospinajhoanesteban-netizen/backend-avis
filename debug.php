<?php
require __DIR__.'/vendor/autoload.php';
use Illuminate\Foundation\Application;

$builder = Application::configure(basePath: __DIR__);
echo "Builder class: " . get_class($builder) . "\n";
echo "Has withMiddleware: " . (method_exists($builder, 'withMiddleware') ? 'Yes' : 'No') . "\n";
echo "Laravel Version: " . Application::VERSION . "\n";
