<?php

declare(strict_types=1);

if (file_exists(dirname(__DIR__) . '/var/cache/prod/App_KernelProdContainer.preload.php')) {
    /**
     * @psalm-suppress MissingFile
     */ 
    require dirname(__DIR__) . '/var/cache/prod/App_KernelProdContainer.preload.php';
}
