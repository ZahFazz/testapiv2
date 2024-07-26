<?php
require_once '../vendor/autoload.php';
use Firebase\JWT\JWT;

class JwtHelper {
    private static $secret_key = "YOUR_SECRET_KEY";
    private static $algorithm = "HS256";

    public static function generateToken($data) {
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600;  // jwt valid for 1 hour from the issued time
        $payload = array(
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'data' => $data
        );

        return JWT::encode($payload, self::$secret_key, self::$algorithm);
    }

    public static function validateToken($token) {
        try {
            $decoded = JWT::decode($token, self::$secret_key, array(self::$algorithm));
            return (array) $decoded->data;
        } catch (Exception $e) {
            return null;
        }
    }
}
?>
