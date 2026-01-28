<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Auth::user()->addresses()->latest()->get();
        return view('profile.addresses.index', compact('addresses'));
    }

    public function create()
    {
        return view('profile.addresses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'nullable|string|max:50',
            'recipient_name' => 'required|string|max:255',
            'zipcode' => 'required|string|max:10',
            'address' => 'required|string|max:255',
            'number' => 'required|string|max:20',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|size:2',
            'is_default' => 'boolean',
        ]);

        if ($validated['is_default'] ?? false) {
            Auth::user()->addresses()->update(['is_default' => false]);
        }

        Auth::user()->addresses()->create($validated);

        return redirect()->route('profile.addresses.index')->with('success', 'Endereço cadastrado com sucesso!');
    }

    public function edit(UserAddress $address)
    {
        $this->authorize('update', $address);
        return view('profile.addresses.edit', compact('address'));
    }

    public function update(Request $request, UserAddress $address)
    {
        $this->authorize('update', $address);

        $validated = $request->validate([
            'label' => 'nullable|string|max:50',
            'recipient_name' => 'required|string|max:255',
            'zipcode' => 'required|string|max:10',
            'address' => 'required|string|max:255',
            'number' => 'required|string|max:20',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|size:2',
            'is_default' => 'boolean',
        ]);

        if ($validated['is_default'] ?? false) {
            Auth::user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update($validated);

        return redirect()->route('profile.addresses.index')->with('success', 'Endereço atualizado com sucesso!');
    }

    public function destroy(UserAddress $address)
    {
        $this->authorize('delete', $address);
        $address->delete();
        return redirect()->route('profile.addresses.index')->with('success', 'Endereço removido com sucesso!');
    }

    public function setDefault(UserAddress $address)
    {
        $this->authorize('update', $address);
        Auth::user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);
        
        return back()->with('success', 'Endereço padrão atualizado!');
    }
}
