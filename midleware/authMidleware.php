<?php
require_once '../jwt/jwtCheck.php';

class AuthMiddleware {
    public static function authenticate() {
        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            $matches = array();
            if (preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
                $token = $matches[1];
                $user = JwtHelper::validateToken($token);
                if ($user) {
                    return $user;
                }
            }
        }
        header('HTTP/1.0 401 Unauthorized');
        echo json_encode(["message" => "Access denied."]);
        exit();
    }
}
?>
