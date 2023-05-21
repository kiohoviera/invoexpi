<?php

namespace App\Http\Controllers;

use App\Imports\InventoryImport;
use App\Models\Inventories;
use App\Models\ScanLogs;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class InventoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $goBack = false;

        return view('Components.dashboard', compact('goBack'));
    }

    public function createInventory()
    {
        //
        $goBack = true;

        return view('Components.insert-inventory', compact('goBack'));
    }

    public function uploadInventory()
    {
        $goBack = true;

        return view('Components.upload', compact('goBack'));
    }

    public function expiringInventory()
    {
        $goBack = true;

        return view('Components.expiring-inventory', compact('goBack'));
    }

    public function importInventory(Request $request)
    {
        Excel::import(new InventoryImport, request()->file('file_upload'));

        return redirect()->back();

    }

    public function getInventory()
    {
        $inventory = Inventories::latest()->get();

        return response()->json($inventory);
    }

    public function getExpiringInventory()
    {
        /*
         * Get those inventory that are expiring
         */

        $inventory = Inventories::whereDate('expiration_date', '<', Carbon::now()->addDays(5))
            ->latest()
            ->get();

        return response()->json($inventory);


    }

    public function getExpiredInventory()
    {
        /*
         * Get all expired date
         */
        $inventory = Inventories::whereDate('expiration_date', '<', Carbon::now())
            ->latest()
            ->get();

        return response()->json($inventory);
    }

    public function searchInventory($key)
    {


        $inventory = Inventories::where('product_name', 'LIKE', '%'.$key.'%')
            ->orWhere('sku', 'LIKE', '%'.$key.'%')
            ->orWhere('expiration_date', 'LIKE', '%'.$key.'%')
            ->latest()
            ->get();

        return response()->json($inventory);
    }

    public function getScanLogs()
    {

        $scanLogs = ScanLogs::where('status', 'Pending')
                        ->latest()->first();
        /*
        * Identify if serial is existing or not
        */

        if ($scanLogs){
            $inventories = Inventories::where('serial_number', $scanLogs->serial_number)
                ->latest()->first();

            if ($inventories) {
                return response()->json($inventories);
            }
        }

        return response()->json($scanLogs);
    }

    public function processLogs(Request $request)
    {
        $serial = $request->post('serial_no');

        $scanLogs = ScanLogs::create([
            'serial_number' => $request->post('serial_no'),
            'expiration_date' => $request->post('exp_date')
        ]);

        return response()->json($scanLogs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //

        /*
         * check the inventory
         */

        DB::beginTransaction();

        try {
            $invCheck = Inventories::where('serial_number', $request->post('serial_number'))
                ->get()->count();

            if ($invCheck > 0) {
                $inventory = Inventories::where('serial_number', $request->post('serial_number'))
                    ->update([
                        'sku' => $request->post('sku'),
                        'product_name' => $request->post('product_name'),
                        'quantity' => $request->post('quantity'),
                        'location' => $request->post('location'),
                        'expiration_date' => $request->post('expiration_date')
                    ]);
            } else {
                $inventory = Inventories::create([
                    'serial_number' => $request->post('serial_number'),
                    'sku' => $request->post('sku'),
                    'product_name' => $request->post('product_name'),
                    'quantity' => $request->post('quantity'),
                    'location' => $request->post('location'),
                    'expiration_date' => $request->post('expiration_date')
                ]);
            }

            ScanLogs::where('status', 'Pending')
                ->update([
                    'status' => 'Completed'
                ]);

            DB::commit();
            return response()->json([
                'message' => 'Update Successfully',
                'type' => 'success',
                'error' => false
            ]);
        }
        catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'message' => $exception->getMessage(),
                'type' => 'error',
                'error' => true
            ]);
        }



    }

    public function updateScanLogs()
    {
        ScanLogs::where('status', 'Pending')
            ->update([
                'status' => 'Completed'
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\inventories  $inventories
     * @return \Illuminate\Http\Response
     */
    public function show(inventories $inventories)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\inventories  $inventories
     * @return \Illuminate\Http\Response
     */
    public function edit(inventories $inventories)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \App\Models\inventories  $inventories
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, inventories $inventories): \Illuminate\Http\Response
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        //

        $inventory = Inventories::find($id);
        if ($inventory){
            $inventory->delete();
            return response()->json([
                'message' => 'Deleted Successfully',
                'deleted' => true
            ]);
        }

        return response()->json([
            'message' => 'Inventory No Longer Exists',
            'deleted' => false
        ]);
    }

    public function deleteExpiredInventory()
    {
        $inventory = Inventories::whereDate('expiration_date', '<', Carbon::now())
            ->delete();

        return response()->json([
            'message' => 'Deleted Successfully'
        ]);
    }
}
