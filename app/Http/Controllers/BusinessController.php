<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Http\Requests\StoreBusinessRequest;
use App\Http\Requests\UpdateBusinessRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBusinessRequest $request)
    {

        $data = $request->validated();

        // Handle logo upload if exists
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo_path'] = $path;
        }

        // Create the store record
        $store = Business::create([
            'user_id' => Auth::id(),
            'business_name' => $data['name'],
            'business_email' => $data['email'],
            'business_phone' => $data['phone'] ?? null,
            'business_address' => $data['address'] ?? null,
            'business_website' => $data['website'] ?? null,
            'tax_id' => $data['tax_id'] ?? null,
            'logo_path' => $data['logo_path'] ?? null,
            'settings' => $data['settings'] ?? null,
        ]);

        return response()->json([
            'message' => 'Store created successfully',
            'store' => $store
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Business $business)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Business $business)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBusinessRequest $request, Business $business)
    {
        $data = $request->validated();
        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if it exists
            if ($business->business_logo_path && Storage::disk('public')->exists($business->business_logo_path)) {
                Storage::disk('public')->delete($business->business_logo_path);
            }

            $path = $request->file('logo')->store('logos', 'public');
            $data['business_logo_path'] = $path;
        }

        $business->update([
            'business_name' => $data['name'],
            'business_email' => $data['email'],
            'business_phone' => $data['phone'] ?? null,
            'business_address' => $data['address'] ?? null,
            'business_website' => $data['website'] ?? null,
            'tax_id' => $data['tax_id'] ?? null,
            'logo_path' => $data['business_logo_path'] ?? $business->business_logo_path,
            'settings' => $data['settings'] ?? $business->business_settings
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Business $business)
    {
        //
    }
}
