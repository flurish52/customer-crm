<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Http\Requests\StoreBusinessRequest;
use App\Http\Requests\UpdateBusinessRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Encoders\JpegEncoder;
//use Intervention\Image\Image;
//use Intervention\Image\Gd\Encoder\Jpeg as JpegEncoder;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia::render('OnBoard', [
            'business' => Business::where('user_id', Auth::id())->first(),
        ]);
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

        if ($request->hasFile('logo')) {

            $file = $request->file('logo');
            $image = Image::read($file)->resize(200, 200);

            // Generate unique name
            $uniqueName = Str::uuid() . '.webp';
            $path = 'logos/' . $uniqueName;

            // Encode and compress to WebP
            $encoded = $image->encode(new WebpEncoder(80));

            // Save optimized file
            Storage::disk('public')->put($path, $encoded);

            $data['logo_path'] = $path;
        }

        $store = Business::create([
            'user_id' => Auth::id(),
            'business_name' => $data['name'],
            'business_email' => $data['email'],
            'business_phone' => $data['phone'] ?? null,
            'business_address' => $data['address'] ?? null,
            'business_website' => $data['website'] ?? null,
            'tax_id' => $data['tax_id'] ?? null,
            'logo_path' => $data['logo_path'] ?? null,
            'settings' => json_encode([
                    'tax_percent' => $data['tax_percent'],
                    'currency' => strtoupper($data['currency']),
                ]) ?? null,
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
        if ($request->hasFile('logo')) {
            if (!empty($business->logo_path) && Storage::disk('public')->exists($business->logo_path)) {
                Storage::disk('public')->delete($business->logo_path);
            }
            $file = $request->file('logo');
            $image = Image::read($file)->resize(200, 200);
            $uniqueName = Str::uuid() . '.webp';
            $path = 'logos/' . $uniqueName;
            $encoded = $image->encode(new WebpEncoder(100));

            Storage::disk('public')->put($path, $encoded);
            $data['business_logo_path'] = $path;
        }


        $business->update([
            'business_name' => $data['name'],
            'business_email' => $data['email'],
            'business_phone' => $data['phone'] ?? null,
            'business_address' => $data['address'] ?? null,
            'business_website' => $data['website'] ?? null,
            'tax_id' => $data['tax_id'] ?? null,
            'logo_path' => $data['business_logo_path'] ?? $business->logo_path,
            'settings' => json_encode([
                'tax_percent' => $data['tax_percent'],
                'currency' => strtoupper($data['currency']),
            ]) ?? $business->business_settings
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
