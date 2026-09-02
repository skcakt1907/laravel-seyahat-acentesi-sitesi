<?php

namespace App\Services;

/**
 * Garanti BBVA Sanal POS — 3D Secure (apiversion=512) hash utilities.
 *
 * Formüller — referans ASP.NET implementasyonundan birebir:
 *
 *   securityData = SHA1(provisionPassword + ('0' + terminalId))            → UPPER HEX
 *
 *   secure3dhash = SHA512(terminalId + orderId + amount + currencyCode
 *                       + successUrl + errorUrl + type + installmentCount
 *                       + storeKey + securityData)                          → UPPER HEX
 *
 *   provisionHash = SHA512(orderId + terminalId + amount + currencyCode
 *                        + securityData)                                    → UPPER HEX
 *
 * Notes:
 *   - terminalId form alanına HAM gider (10093035). Padding ('0' + 10093035 = 010093035)
 *     SADECE SHA1 securityData hesabında kullanılır.
 *   - apiversion alanı hash'e GİRMEZ (sadece form alanı olarak gönderilir).
 *   - successUrl/errorUrl alanları request hash'inde vardır, provision hash'inde yoktur.
 */
final class GarantiHashService
{
    public function __construct(
        private readonly string $terminalId,
        private readonly string $provisionPassword,
        private readonly string $storeKey,
    ) {}

    public function securityData(): string
    {
        $padded = '0' . $this->terminalId; // 9 hane (referans .cs ile birebir)

        return strtoupper(sha1($this->provisionPassword . $padded));
    }

    /**
     * 3D Secure form post için secure3dhash.
     *
     * @param array{
     *     orderid:string, txnamount:string|int, txncurrencycode:string,
     *     successurl:string, errorurl:string, txntype:string,
     *     txninstallmentcount:string|int
     * } $p
     */
    public function secure3DHash(array $p): string
    {
        $raw = $this->terminalId
             . $p['orderid']
             . $p['txnamount']
             . $p['txncurrencycode']
             . $p['successurl']
             . $p['errorurl']
             . $p['txntype']
             . $p['txninstallmentcount']
             . $this->storeKey
             . $this->securityData();

        return strtoupper(hash('sha512', $raw));
    }

    /**
     * VPServlet provizyon XML'i için HashData (3D auth sonrası).
     */
    public function provisionHash(string $orderId, string|int $amount, string $currencyCode = '949'): string
    {
        $raw = $orderId
             . $this->terminalId
             . $amount
             . $currencyCode
             . $this->securityData();

        return strtoupper(hash('sha512', $raw));
    }

    /**
     * Bankanın 3D callback'inde gönderdiği secure3dhash'i doğrula.
     */
    public function verifyCallbackHash(array $post): bool
    {
        if (empty($post['secure3dhash'])) {
            return false;
        }

        $expected = $this->secure3DHash([
            'orderid'             => $post['orderid']             ?? '',
            'txnamount'           => $post['txnamount']           ?? '',
            'txncurrencycode'     => $post['txncurrencycode']     ?? '',
            'successurl'          => $post['successurl']          ?? '',
            'errorurl'            => $post['errorurl']            ?? '',
            'txntype'             => $post['txntype']             ?? '',
            'txninstallmentcount' => $post['txninstallmentcount'] ?? '',
        ]);

        return hash_equals($expected, strtoupper((string) $post['secure3dhash']));
    }
}
