<?php
// app/Policies/TableReservationPolicy.php

namespace App\Policies;

use App\Models\TableReservation;
use App\Models\User;

class TableReservationPolicy
{
    public function viewAny(User $user)
    {
        return $user->role === 'admin' || $user->role === 'reception';
    }

    public function view(User $user, TableReservation $reservation)
    {
        return $user->role === 'admin' 
            || $user->role === 'reception'
            || $user->id === $reservation->user_id;
    }

    public function create(User $user)
    {
        return true; // All authenticated users can create reservations
    }

    public function update(User $user, TableReservation $reservation)
    {
        return $user->role === 'admin'
            || $user->role === 'reception'
            || $user->id === $reservation->user_id;
    }

    public function delete(User $user, TableReservation $reservation)
    {
        return $this->update($user, $reservation);
    }
}