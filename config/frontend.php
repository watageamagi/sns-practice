<?php
return [
    // frontend URL
    'url' => env('APP_URL'),
    // path to my frontend page with query param queryURL(temporarySignedRoute URL)
    'email_verify_url' => env('FRONTEND_EMAIL_VERIFY_URL', '/email/verify?queryURL='),

    'js_model_dir' => env('JS_MODEL_DIR', '/resources/js/models')
];