<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TableController extends Controller
{
    public function __invoke(Request $request) {
        $validated = $request->validate([
            'guests' => 'required|integer|min:1',
            'start' => 'required|date',
            'end' => 'required|date|after:start',
        ]);
        $tables = Table::findTables($validated["guests"], $validated["start"], $validated["end"]);
        return response()->json($tables);
    }
}
