<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SafariRequest extends Model
{
    protected $fillable = [
        'full_name', 'email', 'phone', 'country', 'contact_method',
        'arrival_date', 'departure_date', 'trip_duration', 'flexible_dates',
        'adults', 'children', 'children_ages', 'group_type',
        'destinations', 'experience_types',
        'accommodation_type', 'room_type',
        'transport_type', 'airport_pickup',
        'budget_range',
        'activities',
        'special_requests',
        'consent_privacy',
        'status', 'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'arrival_date' => 'date',
            'departure_date' => 'date',
            'flexible_dates' => 'boolean',
            'airport_pickup' => 'boolean',
            'consent_privacy' => 'boolean',
            'destinations' => 'array',
            'experience_types' => 'array',
            'activities' => 'array',
        ];
    }

    public static function statusOptions(): array
    {
        return [
            'new' => __('New'),
            'contacted' => __('Contacted'),
            'quotation_sent' => __('Quotation Sent'),
            'confirmed' => __('Confirmed'),
            'cancelled' => __('Cancelled'),
        ];
    }

    public function statusLabel(): string
    {
        return self::statusOptions()[$this->status] ?? $this->status;
    }

    public function destinationsSummary(): string
    {
        $d = $this->destinations ?? [];

        return is_array($d) && count($d) > 0
            ? implode(', ', array_slice($d, 0, 4)).(count($d) > 4 ? '…' : '')
            : '—';
    }

    public function budgetLabel(): string
    {
        return match ($this->budget_range) {
            'under_1000' => __('Under $1,000'),
            '1000_2500' => __('$1,000 – $2,500'),
            '2500_5000' => __('$2,500 – $5,000'),
            '5000_plus' => __('$5,000+'),
            'not_sure' => __('Not sure'),
            default => $this->budget_range ? (string) $this->budget_range : '—',
        };
    }

    public function groupTypeLabel(): string
    {
        return match ($this->group_type) {
            'solo' => __('Solo'),
            'couple' => __('Couple'),
            'family' => __('Family'),
            'friends' => __('Friends'),
            'corporate' => __('Corporate'),
            default => $this->group_type ?? '—',
        };
    }

    public function accommodationLabel(): string
    {
        return match ($this->accommodation_type) {
            'luxury_lodge' => __('Luxury Lodge'),
            'mid_lodge' => __('Mid-range Lodge'),
            'budget_camp' => __('Budget Camp'),
            'tented_camp' => __('Tented Camp'),
            'boutique_hotel' => __('Boutique Hotel'),
            default => $this->accommodation_type ?? '—',
        };
    }

    public function roomTypeLabel(): string
    {
        return match ($this->room_type) {
            'single' => __('Single'),
            'double' => __('Double'),
            'twin' => __('Twin'),
            'family' => __('Family room'),
            default => $this->room_type ?? '—',
        };
    }

    public function transportLabel(): string
    {
        return match ($this->transport_type) {
            '4x4_safari' => __('4×4 Safari Vehicle'),
            'land_cruiser' => __('Land Cruiser'),
            'safari_van' => __('Safari Van'),
            'flight_transfer' => __('Flight transfer'),
            'self_drive' => __('Self drive'),
            default => $this->transport_type ?? '—',
        };
    }

    public function contactMethodLabel(): string
    {
        return match ($this->contact_method) {
            'phone' => __('Phone'),
            'email' => __('Email'),
            'whatsapp' => __('WhatsApp'),
            default => $this->contact_method ?? '—',
        };
    }
}
