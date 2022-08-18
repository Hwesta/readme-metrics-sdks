<?php

// Add this code into your `routes/web.php` file.
Route::post('/webhook', function (\Illuminate\Http\Request $request) {
    // Verify the request is legitimate and came from ReadMe.
    $signature = $request->headers->get('readme-signature', '');

    // Your ReadMe secret
    $secret = env('README_API_KEY', config('readme.api_key'));

    try {
        \ReadMe\Webhooks::verify($request->input(), $signature, $secret);
    } catch (\Exception $e) {
        // Handle invalid requests
        return response()->json([
            'error' => $e->getMessage()
        ], 401);
    }

    // Fetch the user from the database and return their data for use with OpenAPI variables.
    // $user = DB::table('users')->where('email', $request->input('email'))->limit(1)->get();
    return response()->json([
        // OAS Security variables
        'api_key' => 'default-api_key-key',
        'http_basic' => ['user' => 'user', 'pass' => 'pass'],
        'http_bearer' => 'default-http_bearer-key',
        'oauth2' => 'default-oauth2-key',
    ]);
});