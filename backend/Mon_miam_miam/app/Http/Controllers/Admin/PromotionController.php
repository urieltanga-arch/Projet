<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Event;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
   public function index(Request $request)
{
    $filter = $request->get('filter', 'all'); // Par défaut: 'all' (afficher les promotions)
    
    if ($filter === 'evenement') {
        // Afficher uniquement les événements
        $promotions = collect(); // Collection vide
        $events = Event::orderBy('event_date', 'desc')->paginate(12);
    } else {
        // Afficher uniquement les promotions (par défaut)
        $promotions = Promotion::orderBy('created_at', 'desc')->paginate(12);
        $events = collect(); // Collection vide
    }
    
    return view('admin.promotions.index', compact('promotions', 'events', 'filter'));
}

    // PROMOTIONS
    public function createPromotion()
    {
        return view('admin.promotions.create-promotion');
    }

    public function storePromotion(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed_amount,free_delivery',
            'value' => 'required|numeric|min:0',
            'min_amount' => 'nullable|numeric|min:0',
            'code' => 'nullable|string|max:255',
            'image_url' => 'nullable|url',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'max_uses' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $validated['current_uses'] = 0;

        Promotion::create($validated);

        return redirect()->route('admin.promotions.index', ['filter' => 'promotion'])
            ->with('success', 'Promotion créée avec succès');
    }

    public function editPromotion(Promotion $promotion)
    {
        return view('admin.promotions.edit-promotion', compact('promotion'));
    }

    public function updatePromotion(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed_amount,free_delivery',
            'value' => 'required|numeric|min:0',
            'min_amount' => 'nullable|numeric|min:0',
            'code' => 'nullable|string|max:255',
            'image_url' => 'nullable|url',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'max_uses' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $promotion->update($validated);

        return redirect()->route('admin.promotions.index', ['filter' => 'promotion'])
            ->with('success', 'Promotion modifiée avec succès');
    }

    public function destroyPromotion(Promotion $promotion)
    {
        $promotion->delete();

        return redirect()->route('admin.promotions.index', ['filter' => 'promotion'])
            ->with('success', 'Promotion supprimée avec succès');
    }

    // ÉVÉNEMENTS
    public function createEvent()
    {
        return view('admin.promotions.create-event');
    }

    public function storeEvent(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:karaoke,football',
            'event_date' => 'required|date',
            'max_participants' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $validated['current_participants'] = 0;

        Event::create($validated);

        return redirect()->route('admin.promotions.index', ['filter' => 'evenement'])
            ->with('success', 'Événement créé avec succès');
    }

    public function editEvent(Event $event)
    {
        return view('admin.promotions.edit-event', compact('event'));
    }

    public function updateEvent(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:karaoke,football',
            'event_date' => 'required|date',
            'max_participants' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $event->update($validated);

        return redirect()->route('admin.promotions.index', ['filter' => 'evenement'])
            ->with('success', 'Événement modifié avec succès');
    }

    public function destroyEvent(Event $event)
    {
        $event->delete();

        return redirect()->route('admin.promotions.index', ['filter' => 'evenement'])
            ->with('success', 'Événement supprimé avec succès');
    }
}