<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::all();
        return view('admin.payment-methods.index', compact('paymentMethods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.payment-methods.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:mobile_money,crypto,bank',
            'details' => 'required|string',
            'is_active' => 'boolean',
        ]);

        PaymentMethod::create([
            'id' => Str::uuid(),
            'name' => $validated['name'],
            'type' => $validated['type'],
            'details' => $validated['details'],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.payment-methods.index')->with('success', 'Payment method created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        return view('admin.payment-methods.edit', compact('paymentMethod'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:mobile_money,crypto,bank',
            'details' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $paymentMethod->update([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'details' => $validated['details'],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.payment-methods.index')->with('success', 'Payment method updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->delete();

        return redirect()->route('admin.payment-methods.index')->with('success', 'Payment method deleted.');
    }
}
