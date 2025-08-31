<?php

namespace App\Helpers;

class RegistrationHelper
{
    /**
     * Check if registration is enabled
     */
    public static function isEnabled(): bool
    {
        return config('auth.registration.enabled', true);
    }

    /**
     * Get allowed email domains
     */
    public static function getAllowedEmailDomains(): array
    {
        return config('auth.registration.emails_allowed', []);
    }

    /**
     * Check if email domain is allowed
     */
    public static function isEmailDomainAllowed(string $email): bool
    {
        $allowedDomains = self::getAllowedEmailDomains();
        
        if (empty($allowedDomains)) {
            return true; // If no restrictions, all domains are allowed
        }

        $emailDomain = substr(strrchr($email, "@"), 1);
        return in_array($emailDomain, $allowedDomains);
    }

    /**
     * Get human-readable list of allowed domains
     */
    public static function getAllowedDomainsString(): string
    {
        $domains = self::getAllowedEmailDomains();
        return empty($domains) ? 'All domains allowed' : implode(', ', $domains);
    }
}
