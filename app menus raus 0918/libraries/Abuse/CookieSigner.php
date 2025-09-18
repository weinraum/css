<?php namespace App\Libraries\Abuse;

use CodeIgniter\HTTP\ResponseInterface;

final class CookieSigner
{
public static function ensureRid(
    ?string $cookie,
    ResponseInterface $resp,
    string $name,
    string $secret,
    bool $secure // <— neu
): string {
    if ($cookie && self::verify($cookie, $secret)) return $cookie;

    $rid   = bin2hex(random_bytes(16));
    $token = self::sign($rid, $secret);

    // Wichtig: Domain, Path, Prefix, Secure, HttpOnly, SameSite
    $resp->setCookie($name, $token, YEAR, '', '/', '', $secure, true, $secure ? 'None' : 'Lax');

    return $token;
}

    public static function sign(string $payload, string $secret): string
    {
        $sig = hash_hmac('sha256', $payload, $secret);
        return $payload.'.'.$sig;
    }

    public static function verify(string $token, string $secret): bool
    {
        $pos = strrpos($token, '.'); if ($pos === false) return false;
        $p = substr($token, 0, $pos); $s = substr($token, $pos+1);
        return hash_equals(hash_hmac('sha256', $p, $secret), $s);
    }
}
