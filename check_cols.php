<?php
$cols = \Illuminate\Support\Facades\Schema::getColumnListing('product_orders');
echo implode(', ', $cols) . PHP_EOL;
