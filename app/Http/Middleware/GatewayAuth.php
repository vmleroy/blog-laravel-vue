<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GatewayAuth
{
    /**
     * Handle an incoming request for the gateway.
     * Validates JWT token and attaches user information to request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'error' => 'Unauthorized - Token not provided',
            ], 401);
        }

        try {
            // Validate and decode JWT token
            $payload = $this->validateToken($token);

            // Attach user information to request
            $request->attributes->add([
                'user' => $payload,
                'token' => $token,
            ]);

            return $next($request);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Unauthorized - ' . $e->getMessage(),
            ], 401);
        }
    }

    /**
     * Validate JWT token and return payload
     *
     * @param string $token
     * @return array
     * @throws \Exception
     */
    private function validateToken(string $token): array
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            throw new \Exception('Invalid token format');
        }

        [$headerEncoded, $payloadEncoded, $signatureEncoded] = $parts;

        // Decode payload
        $payload = json_decode(base64_decode($payloadEncoded), true);

        if (!$payload) {
            throw new \Exception('Invalid token payload');
        }

        // Verify signature
        $secret = $this->getSecretKey();
        $signature = hash_hmac('sha256', "{$headerEncoded}.{$payloadEncoded}", $secret, true);
        $signatureBase64 = base64_encode($signature);

        if (!hash_equals($signatureBase64, $signatureEncoded)) {
            throw new \Exception('Invalid token signature');
        }

        // Check expiration
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            throw new \Exception('Token expired');
        }

        return $payload;
    }

    /**
     * Get the secret key for JWT validation
     * Handles base64-encoded keys like 'base64:xxx'
     */
    private function getSecretKey(): string
    {
        $key = config('app.key');
        if (str_starts_with($key, 'base64:')) {
            return base64_decode(substr($key, 7));
        }
        return $key;
    }
}
