<?php

/**
 * gethostbyaddr() wrapper
 *
 * @copyright 2023 Sigsign
 * @license Apache-2.0 or MIT-0
 * @author Sigsign <sig@signote.cc>
 */

declare(strict_types=1);

namespace Relight;

use Symfony\Component\HttpFoundation\IpUtils;

class RemoteHost
{
    private string $ip;
    private string $hostname;
    private bool $isIPv4;

    public function __construct(string $ip, string $hostname = '')
    {
        $r = \filter_var($ip, FILTER_VALIDATE_IP, FILTER_NULL_ON_FAILURE);
        if (\is_null($r)) {
            throw new \InvalidArgumentException("Invalid IP address: {$ip}");
        }
        $this->ip = $r;

        $r = \filter_var($hostname, FILTER_VALIDATE_DOMAIN, FILTER_NULL_ON_FAILURE | FILTER_FLAG_HOSTNAME);
        $this->hostname = \is_string($r) ? $r : '';

        $r = \filter_var($ip, FILTER_VALIDATE_IP, FILTER_NULL_ON_FAILURE | FILTER_FLAG_IPV4);
        $this->isIPv4 = \is_string($r);
    }

    public function equalTo(RemoteHost $other)
    {
        return $this->ip === $other->ip;
    }

    public function getHostname(): string
    {
        // IPv6 は逆引きに頼ってはいけない
        if ($this->isIPv6()) {
            return '';
        }

        if ($this->hostname !== '') {
            return $this->hostname;
        }

        // Double reverse lookup
        $hostname = \gethostbyaddr($this->ip);
        if ($hostname !== false && $hostname !== $this->ip) {
            $ip = \gethostbyname($hostname);
            if ($ip === $this->ip) {
                $this->hostname = $hostname;
            }
        }

        return $this->hostname;
    }

    public function getIP(): string
    {
        return $this->ip;
    }

    /**
     * IP アドレスが引数の IP range に含まれるか否かを返す
     *
     * @param string|array $ips
     * @return boolean
     */
    public function included(mixed $ips): bool
    {
        if (!\is_string($ips) && !\is_array($ips)) {
            throw new \InvalidArgumentException('Require String or Array');
        }

        return IpUtils::checkIp($this->ip, $ips);
    }

    public function isIPv4(): bool
    {
        return $this->isIPv4;
    }

    public function isIPv6(): bool
    {
        return !$this->isIPv4;
    }

    /**
     * リモートホストが引数の正規表現に含まれるか否かを返す
     *
     * @param string|array $pattern
     * @param array|null $matches
     * @return boolean
     */
    public function match(mixed $pattern, array &$matches = null): bool
    {
        if (!\is_string($pattern) && !\is_array($pattern)) {
            throw new \InvalidArgumentException('Require String or Array');
        }

        if (\is_array($pattern)) {
            foreach ($pattern as $p) {
                if ($this->match($p, $matches)) {
                    return true;
                }
            }
            return false;
        }

        $r = \preg_match($pattern, $this->getHostname(), $matches);
        if ($r === false) {
            throw new \InvalidArgumentException(\preg_last_error_msg());
        }

        return $r === 1 ? true : false;
    }

    public function __toString()
    {
        $ip = $this->getIP();
        $hostname = $this->getHostname();

        return $hostname === '' ? $ip : "{$hostname} ({$ip})";
    }
}
