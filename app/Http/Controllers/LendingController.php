<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Lending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LendingController extends Controller
{
    public function index()
    {
        $lendings = Lending::with('user')->latest()->get();
        $items    = Item::all();
        return view('operator.lending', compact('lendings', 'items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'date'      => 'required|date',
            'item_id'   => 'required|array|min:1',
            'item_id.*' => 'required|exists:items,id',
            'total'     => 'required|array|min:1',
            'total.*'   => 'required|integer|min:1',
        ]);

        foreach ($request->item_id as $i => $itemId) {
            $item = Item::find($itemId);
            if ($request->total[$i] > $item->total) {
                return back()->withInput()->with('overlimit', 'Total item more than available!');
            }
        }

        $items = [];
        foreach ($request->item_id as $i => $itemId) {
            $item    = Item::find($itemId);
            $items[] = ['item_id' => $itemId, 'name' => $item->name, 'total' => $request->total[$i]];
            $item->decrement('total', $request->total[$i]);
        }

        Lending::create([
            'user_id'  => Auth::id(),
            'name'     => $request->name,
            'items'    => $items,
            'ket'      => $request->ket,
            'date'     => $request->date,
            'returned' => false,
            'edit_by'  => Auth::user()->name,
            'back_by'  => null,
        ]);

        return redirect()->route('operator.lending')->with('success', 'Success add new lending item!');
    }

    public function returned(Lending $lending)
    {
        foreach ($lending->items as $li) {
            Item::find($li['item_id'])->increment('total', $li['total']);
        }

        $lending->update(['returned' => true, 'back_by' => Auth::user()->name]);

        return redirect()->route('operator.lending')->with('success', 'Item is returned!');
    }

    public function destroy(Lending $lending)
    {
        if (!$lending->returned) {
            foreach ($lending->items as $li) {
                Item::find($li['item_id'])->increment('total', $li['total']);
            }
        }

        $lending->delete();
        return redirect()->route('operator.lending')->with('success', 'Lending berhasil dihapus.');
    }

    public function export()
    {
        $lendings = Lending::with('user')->get();
        $filename = 'lendings_' . now()->format('Ymd_His') . '.xlsx';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray(['Item', 'Total', 'Name', 'Ket.', 'Date', 'Return Date', 'Edit By', 'Back By'], null, 'A1');

        $row = 2;
        foreach ($lendings as $lending) {
            $returnDate = $lending->returned
                ? $lending->updated_at->format('M d, Y')
                : '-';

            foreach ($lending->items as $li) {
                $sheet->fromArray([
                    $li['name'],
                    $li['total'],
                    $lending->name,
                    $lending->ket,
                    \Carbon\Carbon::parse($lending->date)->format('M d, Y'),
                    $returnDate,
                    $lending->edit_by,
                    $lending->back_by ?? '-',
                ], null, 'A' . $row++);
            }
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
    }
}
