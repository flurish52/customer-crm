<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Prospect;
use App\Http\Requests\StoreProspectRequest;
use App\Http\Requests\UpdateProspectRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProspectController extends Controller
{

    private function copyToCustomer(Prospect $prospect)
    {
        $exists = Customer::where('user_id', $prospect->user_id)
            ->where(function ($q) use ($prospect) {
                $q->where('email', $prospect->email)
                    ->orWhere('phone', $prospect->phone);
            })
            ->exists();
        if ($exists) {
            throw new \Exception('Client with this email or phone already exists on your client list.');
        }
        return Customer::create([
            'name' => $prospect->name,
            'email' => $prospect->email,
            'phone' => $prospect->phone,
            'user_id' => $prospect->user_id,
            'company' => $prospect->company,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia::render('Prospect/List', [
            'prospects' => Prospect::orderBy('created_at', 'DESC')->with('prospectActivities')
                ->where('user_id', Auth::id())
                ->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProspectRequest $request)
    {
        $data = $request->validated();
        if (!empty($data['customSource'])) {
            $data['source'] = $data['customSource'];
        }
        unset($data['customSource']);
        $data['user_id'] = auth()->id();
        $prospect = Prospect::create($data);
        if ($prospect->status === 'converted') {
            $this->copyToCustomer($prospect);
        }

        return response()->json([
            'message' => 'Prospect saved successfully',
            'prospect' => $prospect
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Prospect $prospect)
    {
        $prospect->load('prospectActivities');

        return inertia('Prospect/Show', [
            'prospect' => $prospect,
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prospect $prospect)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProspectRequest $request, Prospect $prospect)
    {
        // $prospect is automatically resolved by route model binding

        $prospect->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company' => $request->company,
            'source' => $request->source ?? $request->customSource, // fallback
            'status' => $request->status,
        ]);
        $prospect->save();
        if ($prospect->status === 'converted') {
            $this->copyToCustomer($prospect);
        }

        return response()->json([
            'message' => 'Prospect updated successfully',
            'prospect' => $prospect->fresh()
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function updateStatus(Request $request, Prospect $prospect)
    {
        $request->validate([
            'status' => 'required|string'
        ]);
        if (Auth::id() !== $prospect->user_id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }
        $prospect->status = $request->status;
        $prospect->save();
        if ($prospect->status === 'converted') {
            $this->copyToCustomer($prospect);
        }
        return response()->json([
            'message' => 'Prospect status updated successfully',
            'status' => $prospect->status
        ]);
    }

    public function destroy(Prospect $prospect)
    {
//        dd(Carbon::now()->toDateString());
        if (Auth::id() === $prospect->user_id) {
//            $prospect->deleted_at = Carbon::now()->toDateString();
            $prospect->delete();
            return response()->json([
                'message' => 'Prospect deleted successfully'
            ]);
        }
    }

}
