<?php

return [
    'path' => app_path('Modules'),
    'core' => ['core_auth','core_settings','core_modules','core_notifications','core_audit'],
    'presets' => [
        'fashion' => ['channels','inbox','chat_to_order','ai_assistant','orders','customers','catalog','inventory','packing','couriers','returns','payments','coupons','campaigns','reviews','storefront','dashboards','documents'],
        'gadgets' => ['channels','inbox','chat_to_order','ai_assistant','orders','customers','catalog','inventory','purchases','packing','couriers','returns','payments','accounting','crm','tickets','dashboards','documents'],
        'food' => ['channels','inbox','chat_to_order','orders','customers','catalog','inventory','packing','couriers','payments','cash','campaigns','reviews','dashboards','hr','documents'],
    ],
];
