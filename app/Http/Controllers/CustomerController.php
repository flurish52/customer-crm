<?php

namespace App\Http\Controllers;

use App\Models\ActivityWithClient;
use App\Models\Business;
use App\Models\Customer;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Invoice;
use App\Models\Job;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Encoders\JpegEncoder;

// add this

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $customers = Customer::orderBy('created_at', 'DESC')
            ->with('jobs.invoices.payments', 'invoices.payments', 'activities')
            ->where('user_id', Auth::id())->get();
    }

    public function getReceivables()
    {
        $total_amount = Payment::where('user_id', Auth::id())->where('is_invalid', false)->sum('amount_in_business_currency');
        return $total_amount;
    }

    public function returnCustomers()
    {
        return inertia::render('User/CustomerPage', [

        ]);
    }

    public function returnReports()
    {
        return inertia::render('User/Reports', [

        ]);
    }

    public function viewCustomer($customer_id)
    {
        $user_id = Auth::user()->id;
        return inertia::render('User/Customer', [
            'customer' => Customer::with([
                'jobs' => function ($query) {
                    $query->orderBy('created_at', 'desc'); // or 'asc' for oldest first
                },
                'jobs.customer',
                'jobs.invoices.payments',
                'jobs.activities',
                'activities'
            ])
                ->where('id', $customer_id)
                ->where('user_id', $user_id)
                ->first(),


            'totalSpent' => Payment::where('customer_id', $customer_id)->where('user_id', Auth::id())->sum('amount_in_business_currency'),
            'invoices' => Invoice::with('items', 'customer', 'job', 'payments')
                ->where('customer_id', $customer_id)
                ->where('user_id', Auth::id())
                ->get(),
            'business' => Business::where('user_id', Auth::id())->first(),
            'jobs' => Job::orderBy('created_at', 'DESC')->with('activities', 'customer')
                ->where('user_id', Auth::id())->get(),
            'activities' => ActivityWithClient::orderBy('activity_date', 'DESC')
            ->where('user_id', Auth::id())
            ->where('customer_id', $customer_id)
            ->get(),
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
    public function store(StoreCustomerRequest $request)
    {
        $data = $request->validated();
        $data['company'] = $data['company'] ?? 'Individual';
        $data['user_id'] = auth()->id();

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $image = Image::read($file)->resize(200, 200);

            $encoded = $image->encode(new JpegEncoder(80));

// For avatar


            // Generate unique name with jpg
            $uniqueName = Str::uuid() . '.jpg';
            $path = 'logos/' . $uniqueName;

            // Encode as JPEG with quality 80
            $encoded = $image->encode('jpg', 80);

            // Save optimized file
            Storage::disk('public')->put($path, $encoded);

            $data['logo_path'] = $path;
        }

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');

            $fileName = uniqid() . '.jpg';
            $path = 'customer_avatar/' . $fileName;

            $image = Image::read($file->getRealPath());
            $encoded = $image->encode(new JpegEncoder(80));

            Storage::disk('public')->put($path, $encoded);
            $data['avatar'] = $path;
        }

        $customer = Customer::create($data);

        return response()->json(['message' => 'Customer created', 'customer' => $customer]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, $customer_id)
    {
        $customer = Customer::findOrFail($customer_id);
        DB::beginTransaction();

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');

            // Delete existing avatar if present
            if ($customer->avatar && Storage::disk('public')->exists($customer->avatar)) {
                Storage::disk('public')->delete($customer->avatar);
            }

            // Generate unique file name
            $fileName = uniqid() . '.jpg';
            $path = 'customer_avatar/' . $fileName;

            // Read, resize, and compress image using JpegEncoder
            $image = Image::read($file->getRealPath())->resize(200, 200);
            $encoded = $image->encode(new JpegEncoder(80));

            // Save to storage
            Storage::disk('public')->put($path, $encoded);
        } else {
            $path = $customer->avatar; // keep existing avatar if no new upload
        }

// Update customer data
        $customer->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'company' => $request->input('company'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'note' => $request->input('note'),
            'avatar' => $path,
        ]);

        DB::commit();

        return redirect()->back()->with('success', 'Client updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        if ($customer->avatar && Storage::exists($customer->avatar)) {
            Storage::delete($customer->avatar);
        }
        $customer->delete();
        return response()->json([
            'message' => 'Customer deleted successfully'
        ]);
    }
}
