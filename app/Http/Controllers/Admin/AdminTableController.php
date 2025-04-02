<?php

namespace App\Http\Controllers\Admin;

use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AdminTableController extends Controller
{
    public function create(Request $request)
    {
        $validated = $request->validate([
            'number' => 'required|integer|unique:tables',
            'seats' => 'required|integer|min:1',
        ]);

        $table = Table::create($validated);

        return response()->json($table, 201);
    }

    public function read(Request $request)
    {
        $page = $request->input('page', 1);
        $tables = Table::paginate(20, ['*'], 'page', $page);

        return response()->json($tables);
    }

    public function update(Request $request, $id)
    {
        $table = Table::findOrFail($id);

        $validated = $request->validate([
            'number' => 'integer|unique:tables,number,' . $table->id,
            'seats' => 'integer|min:1',
        ]);

        $table->update($validated);

        return response()->json($table);
    }

    public function delete($id)
    {
        $table = Table::findOrFail($id);
        $table->delete();

        return response()->json(null, 204);
    }
}
