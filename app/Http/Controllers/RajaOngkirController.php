<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use Exception;
use Illuminate\Http\Request;
use Kavist\RajaOngkir\Facades\RajaOngkir;

class RajaOngkirController extends Controller
{
    public function cek_ongkir(Request $request)
    {
        // return response()->json($request->all());
        try {
            if ($request->weight > 30000) {
                return ResponseFormatter::success(['warning' => true], 'Jumlah berat produk yang dibeli maksimal 30KG');
            }

            $cost = RajaOngkir::ongkosKirim([
                'origin' => $request->city_from,
                'destination' => $request->city_to,
                'weight' => $request->weight,
                'courier' => $request->courierCode
            ])->get();
            // return response()->json($cost);
            return ResponseFormatter::success($cost, 'Cek Ongkir gagal dilakukan');
        } catch (Exception $e) {
            return ResponseFormatter::error(['errors' => $e], 'Cek Ongkir gagal dilakukan');
        }
    }
}
