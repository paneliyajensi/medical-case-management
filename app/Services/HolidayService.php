<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HolidayService
{
    /**
     * Fetch upcoming public holidays from Nager.Date API using Laravel HTTP client.
     * Sets a timeout and handles failures gracefully.
     *
     * @return array
     */
    public function getUpcomingHolidays(): array
    {
        $apiUrl = env('PUBLIC_HOLIDAYS_API_URL', 'https://date.nager.at/api/v3/NextPublicHolidaysWorldwide');

        try {
            // Set 3-second timeout and handle failures gracefully
            $response = Http::timeout(3)
                ->acceptJson()
                ->get($apiUrl);

            if ($response->successful()) {
                $holidays = $response->json();
                if (is_array($holidays)) {
                    // Return first 3 upcoming holidays for surgical scheduling context
                    return array_slice($holidays, 0, 3);
                }
            }
        } catch (\Throwable $e) {
            // Log notice and gracefully return empty list without crashing user view
            Log::info('Public holidays API unavailable or timed out: ' . $e->getMessage());
        }

        return [];
    }
}
