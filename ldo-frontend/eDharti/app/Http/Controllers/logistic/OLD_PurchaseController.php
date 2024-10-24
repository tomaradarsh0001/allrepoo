<?php

namespace App\Http\Controllers\logistic;

use App\Http\Controllers\Controller;
use App\Models\LogisticAvailableStock;
use App\Models\LogisticCategory;
use App\Models\LogisticItem;
use App\Models\LogisticsStockHistory;
use App\Models\Purchase;
use App\Models\SupplierVendorDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\CommonService;

class OLD_PurchaseController extends Controller
{
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function index()
    {
        $purchases = Purchase::with('logisticItem')
            ->select('purchase_id', 'purchased_date')
            ->groupBy('purchase_id', 'purchased_date')
            ->get()
            ->map(function ($purchase) {
                $purchase->items = Purchase::where('purchase_id', $purchase->purchase_id)->get();
                return $purchase;
            });

        return view('logistics.purchase.index', ['purchases' => $purchases]);
    }

    public function create()
    {
        $purchaseItem = LogisticItem::get();
        $purchaseCategory = LogisticCategory::get();
        $purchaseVendor = SupplierVendorDetails::get();
        return view('logistics.purchase.create', compact('purchaseItem', 'purchaseCategory', 'purchaseVendor'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_supplier_id' => 'required',
            'purchased_date' => 'required|date',
            'category_id' => 'required',
            'logistic_items_id' => 'required',
            'purchased_unit' => 'required|integer',
            'per_unit_cost' => 'nullable|numeric|min:0',
            'repeater.*.category_id' => 'sometimes|required',
            'repeater.*.logistic_items_id' => 'sometimes|required',
            'repeater.*.purchased_unit' => 'sometimes|required|integer|min:1',
            'repeater.*.per_unit_cost' => 'sometimes|nullable|numeric|min:0',
        ]);

        $totalCost = !empty($validated['per_unit_cost']) ? $validated['purchased_unit'] * $validated['per_unit_cost'] : 0;

        $purchaseId = $this->commonService->getUniqueID(Purchase::class, 'PR', 'purchase_id');

        $items = Purchase::create([
            'purchase_id' => $purchaseId,
            'vendor_supplier_id' => $validated['vendor_supplier_id'],
            'purchased_date' => $validated['purchased_date'],
            'category_id' => $validated['category_id'],
            'logistic_items_id' => $validated['logistic_items_id'],
            'purchased_unit' => $validated['purchased_unit'],
            'per_unit_cost' => $validated['per_unit_cost'],
            'total_cost' => $totalCost,
            'updated_by' => Auth::id(),
            'created_by' => Auth::id(),
        ]);

        if (!empty($validated['repeater'])) {
            foreach ($validated['repeater'] as $repeater) {
                $repeaterTotalCost = !empty($repeater['per_unit_cost']) ? $repeater['purchased_unit'] * $repeater['per_unit_cost'] : 0;
                // Create Purchase record
                $purchase = Purchase::create([
                    'purchase_id' => $purchaseId,
                    'vendor_supplier_id' => $validated['vendor_supplier_id'],
                    'purchased_date' => $validated['purchased_date'],
                    'category_id' => $repeater['category_id'],
                    'logistic_items_id' => $repeater['logistic_items_id'],
                    'purchased_unit' => $repeater['purchased_unit'],
                    'per_unit_cost' => $repeater['per_unit_cost'],
                    'total_cost' => $repeaterTotalCost,
                    'updated_by' => Auth::id(),
                    'created_by' => Auth::id(),
                ]);

                // Update or Create LogisticAvailableStock
                $stock = LogisticAvailableStock::where('logistic_items_id', $repeater['logistic_items_id'])->first();

                if ($stock) {
                    $stock->available_units += $repeater['purchased_unit'];
                    $stock->save();
                } else {
                    LogisticAvailableStock::create([
                        'logistic_items_id' => $repeater['logistic_items_id'],
                        'available_units' => $repeater['purchased_unit'],
                        'used_units' => 0,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);
                }

                // Fetch updated available_units for history
                $newGrossTotal = LogisticAvailableStock::where('logistic_items_id', $repeater['logistic_items_id'])->pluck('available_units')->first();

                // Create LogisticsStockHistory
                LogisticsStockHistory::create([
                    'logistic_items_id' => $repeater['logistic_items_id'],
                    'category_id' => $repeater['category_id'],
                    'purchase_id' => $purchase->id,
                    'purchase_unique_id' => $purchase->purchase_id,
                    'available_units' => $newGrossTotal,
                    'last_units' => $newGrossTotal - $repeater['purchased_unit'],
                    'last_added_units' => $repeater['purchased_unit'],
                    'last_added_date' => $validated['purchased_date'],
                    'issued_by' => Auth::id(),
                    'issued_at' => now(),
                    'action' => 'purchase',
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
            }
        }

        $stock = LogisticAvailableStock::where('logistic_items_id', $items->logistic_items_id)->first();

        if ($stock) {
            $stock->available_units += $items->purchased_unit;
            $stock->save();
        } else {
            LogisticAvailableStock::create([
                'logistic_items_id' => $items->logistic_items_id,
                'available_units' => $items->purchased_unit,
                'used_units' => 0,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);
        }

        $logisticAvailableStock = LogisticAvailableStock::where('logistic_items_id', $request->logistic_items_id)->pluck('available_units');
        if ($logisticAvailableStock) {
            $newGrossTotal = $logisticAvailableStock[0];

            LogisticsStockHistory::create([
                'logistic_items_id' => $items->logistic_items_id,
                'category_id' => $items->category_id,
                'purchase_id' => $items->id,
                'purchase_unique_id' => $items->purchase_id,
                'available_units' => $newGrossTotal,
                'last_units' => $newGrossTotal - $items->purchased_unit,
                'last_added_units' => $items->purchased_unit,
                'last_added_date' => $items->purchased_date,
                'issued_by' => Auth::id(),
                'issued_at' => now(),
                'action' => 'purchase',
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);
        }

        return redirect('logistic/purchase')->with('success', 'Logistic Purchase successfully.');
    }

    public function indexHistory()
    {
        $history = LogisticsStockHistory::all();
        return view('logistics.history.index', ['history' => $history]);
    }

    public function stockIndex()
    {
        $available = LogisticAvailableStock::all();
        return view('logistics.available.index', ['available' => $available]);
    }

    public function edit($purchaseId)
    {
        $purchaseItems = Purchase::where('purchase_id', $purchaseId)->with(['SupplierVendorDetails', 'logisticCategory', 'logisticItem'])->get();
        $purchaseItem = LogisticItem::all();
        $purchaseCategory = LogisticCategory::all();
        $purchaseVendor = SupplierVendorDetails::all();

        return view('logistics.purchase.edit', compact('purchaseItems', 'purchaseItem', 'purchaseCategory', 'purchaseVendor', 'purchaseId'));
    }

    public function getAvailableUnits($logisticItemId)
    {
        $availableStock = LogisticAvailableStock::where('logistic_items_id', $logisticItemId)->first();
        return response()->json(['available_units' => $availableStock ? $availableStock->available_units : 0]);
    }

    
    public function update(Request $request, $purchaseId)
    {
        $validated = $request->validate([
            'vendor_supplier_id' => 'required',
            'purchased_date' => 'required|date',
            'repeater.*.category_id' => 'sometimes|required',
            'repeater.*.logistic_items_id' => 'sometimes|required',
            'repeater.*.purchased_unit' => 'nullable|integer|min:1',
            'repeater.*.reduced_unit' => 'nullable|integer|min:1',
            'repeater.*.per_unit_cost' => 'sometimes|nullable|numeric|min:0',
        ]);
    
        foreach ($request->input('repeater', []) as $index => $repeater) {
            if (empty($repeater['purchased_unit']) && empty($repeater['reduced_unit'])) {
                return back()->withErrors(['repeater.' . $index . '.purchased_unit' => 'Either purchased unit or reduced unit must be entered.'])
                    ->withInput();
            }
        }
    
        if (!empty($validated['repeater'])) {
            foreach ($validated['repeater'] as $repeater) {
                if (!empty($repeater['purchased_unit']) && !empty($repeater['reduced_unit'])) {
                    continue;
                }
    
                if (!empty($repeater['purchased_unit'])) {
                    $totalCost = !empty($repeater['per_unit_cost']) ? $repeater['purchased_unit'] * $repeater['per_unit_cost'] : 0;
                    $newItem = Purchase::create([
                        'purchase_id' => $purchaseId,
                        'vendor_supplier_id' => $validated['vendor_supplier_id'],
                        'purchased_date' => $validated['purchased_date'],
                        'category_id' => $repeater['category_id'],
                        'logistic_items_id' => $repeater['logistic_items_id'],
                        'purchased_unit' => $repeater['purchased_unit'],
                        'reduced_unit' => null,
                        'per_unit_cost' => $repeater['per_unit_cost'],
                        'total_cost' => $totalCost,
                        'updated_by' => Auth::id(),
                        'created_by' => Auth::id(),
                    ]);
    
                    // Update or create available stock
                    $stock = LogisticAvailableStock::where('logistic_items_id', $repeater['logistic_items_id'])->first();
                    if ($stock) {
                        $stock->available_units += $repeater['purchased_unit'];
                        $stock->save();
                    } else {
                        LogisticAvailableStock::create([
                            'logistic_items_id' => $repeater['logistic_items_id'],
                            'available_units' => $repeater['purchased_unit'],
                            'used_units' => 0,
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                        ]);
                    }
    
                    // Create stock history
                    $newGrossTotal = LogisticAvailableStock::where('logistic_items_id', $repeater['logistic_items_id'])->pluck('available_units')->first();
    
                    LogisticsStockHistory::create([
                        'logistic_items_id' => $repeater['logistic_items_id'],
                        'category_id' => $repeater['category_id'],
                        'purchase_id' => $newItem->id,
                        'purchase_unique_id' => $newItem->purchase_id,
                        'available_units' => $newGrossTotal,
                        'last_units' => $newGrossTotal - $repeater['purchased_unit'],
                        'last_added_units' => $repeater['purchased_unit'],
                        'last_added_date' => $validated['purchased_date'],
                        'issued_by' => Auth::id(),
                        'issued_at' => now(),
                        'action' => 'purchase',
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);
                }
    
                if (!empty($repeater['reduced_unit'])) {
                    $totalCost = 0;
    
                    $newItem = Purchase::create([
                        'purchase_id' => $purchaseId,
                        'vendor_supplier_id' => $validated['vendor_supplier_id'],
                        'purchased_date' => $validated['purchased_date'],
                        'category_id' => $repeater['category_id'],
                        'logistic_items_id' => $repeater['logistic_items_id'],
                        'purchased_unit' => null,
                        'reduced_unit' => $repeater['reduced_unit'],
                        'per_unit_cost' => $repeater['per_unit_cost'],
                        'total_cost' => $totalCost,
                        'updated_by' => Auth::id(),
                        'created_by' => Auth::id(),
                    ]);
    
                    // Update available stock
                    $stock = LogisticAvailableStock::where('logistic_items_id', $repeater['logistic_items_id'])->first();
                    if ($stock) {
                        $stock->available_units -= $repeater['reduced_unit'];
                        $stock->save();
                    }
    
                    // Fetch last issued and reduced entries
                    $lastIssuedEntry = LogisticsStockHistory::where('logistic_items_id', $repeater['logistic_items_id'])
                        ->whereNotNull('issued_units')
                        ->latest()
                        ->first();
    
                    $lastReducedEntry = LogisticsStockHistory::where('logistic_items_id', $repeater['logistic_items_id'])
                        ->whereNotNull('reduced_unit')
                        ->latest()
                        ->first();
    
                    // Determine the latest entry between issued and reduced
                    if ($lastIssuedEntry && $lastReducedEntry) {
                        $lastEntry = $lastIssuedEntry->issued_at > $lastReducedEntry->created_at ? $lastIssuedEntry : $lastReducedEntry;
                    } else {
                        $lastEntry = $lastIssuedEntry ?: $lastReducedEntry;
                    }
    
                    $lastReducedUnits = $lastEntry ? ($lastEntry->issued_units ?? $lastEntry->reduced_unit) : 0;
                    $lastReducedDate = $lastEntry ? $lastEntry->created_at : null;
    
                    // Create stock history
                    $newGrossTotal = LogisticAvailableStock::where('logistic_items_id', $repeater['logistic_items_id'])->pluck('available_units')->first();
    
                    LogisticsStockHistory::create([
                        'logistic_items_id' => $repeater['logistic_items_id'],
                        'category_id' => $repeater['category_id'],
                        'purchase_id' => $newItem->id,
                        'purchase_unique_id' => $newItem->purchase_id,
                        'available_units' => $newGrossTotal,
                        'reduced_unit' => $repeater['reduced_unit'],
                        'last_units' => $newGrossTotal + $repeater['reduced_unit'],
                        'last_reduced_units' => $lastReducedUnits,
                        'last_reduced_date' => $lastReducedDate,
                        'issued_by' => Auth::id(),
                        'issued_at' => now(),
                        'action' => 'purchase',
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);
                }
            }
        }
    
        return redirect('logistic/purchase')->with('success', 'Logistic Purchase updated successfully.');
    }  

    public function destroy($id)
    {
        $item = Purchase::find($id);
        $item->delete();
        return redirect('logistic/purchase')->with('success', 'Purchase Deleted Successfully');
    }
}
