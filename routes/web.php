<?php

foreach (config('tenancy.central_domains') as $domain) {
    require __DIR__ . '/central.php';
}
