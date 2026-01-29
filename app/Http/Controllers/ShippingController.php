<?php

namespace App\Http\Controllers;

use App\Services\CorreiosService;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function calculate(Request $request, CorreiosService $correiosService)
    {
        $request->validate([
            'zipcode' => 'required|string',
        ]);

        $originZip = '26600-000'; // CEP da sua loja (Paracambi - RJ)
        $destZip = $request->zipcode;

        $options = $correiosService->calculateShipping($originZip, $destZip);

        return response()->json([
            'success' => true,
            'options' => $options
        ]);
    }
}
