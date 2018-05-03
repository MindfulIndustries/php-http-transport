<?php

if (!function_exists('build_response')) {
    function build_response($request)
    {
        return response()->json([
            'headers' => $request->header(),
            'query' => $request->query(),
            'json' => $request->json()->all(),
            'form_params' => $request->request->all(),
        ], $request->header('testing-response-code', 200));
    }
}