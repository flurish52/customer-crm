<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Customer;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $customers = Customer::orderBy('created_at', 'DESC')->with('jobs', 'activities')->where('user_id', auth()->user()->id)->get();
    }

    public function getReceivables()
    {
        $total_receiveables = Activity::where('type', 'payment')->get();

        $total_amount = $total_receiveables->sum(function ($activity) {
            $changes = json_decode($activity->changes, true);
            return $changes['amount'] ?? 0;
        });

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
        $user_id =  Auth::user()->id;
        return  inertia::render('User/Customer', [
            'customer' => Customer::with('jobs', 'activities')->where('id', $customer_id)->where('user_id', $user_id)->first(),
            'totalSpent' => Job::where('customer_id', $customer_id)->sum('amount'),
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

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('customer_avatar', 'public');
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

        // Update basic fields
        $customer->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'company' => $request->input('company'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'note' => $request->input('note'),
        ]);

        // Handle image upload if present
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');

            // Optional: delete old image if stored
            if ($customer->avatar) {
                Storage::disk('public')->delete($customer->avatar);
            }

            $customer->update([
                'avatar' => $path,
            ]);
        }

        return redirect()->back()->with('success', 'Customer updated');
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
