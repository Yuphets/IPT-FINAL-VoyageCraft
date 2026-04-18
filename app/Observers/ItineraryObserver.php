<?php

namespace App\Observers;

use App\Models\Itinerary;

class ItineraryObserver
{
    /**
     * Handle the Itinerary "created" event.
     */
    public function created(Itinerary $itinerary): void
    {
        //
    }

    /**
     * Handle the Itinerary "updated" event.
     */
    public function updated(Itinerary $itinerary): void
    {
        //
    }

    /**
     * Handle the Itinerary "deleted" event.
     */
    public function deleted(Itinerary $itinerary): void
    {
        //
    }

    /**
     * Handle the Itinerary "restored" event.
     */
    public function restored(Itinerary $itinerary): void
    {
        //
    }

    /**
     * Handle the Itinerary "force deleted" event.
     */
    public function forceDeleted(Itinerary $itinerary): void
    {
        //
    }
}
