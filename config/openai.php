<?php

return [
    'api_key' => env('OPENAI_API_KEY'),
    'organization' => env('OPENAI_ORGANIZATION', null),
    'api_url' => 'https://api.openai.com/v1',
    'model' => 'gpt-3.5-turbo',
];
