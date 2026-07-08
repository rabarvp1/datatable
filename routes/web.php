<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Snawbar\DataTable\Services\Column;

Route::post('/datatable/columns', function (Request $request) {
    $request->validate([
        'tableId' => 'required|string',
        'className' => 'required|string',
        'columns' => 'nullable|array',
    ]);

    abort_unless(class_exists($request->className), 404, 'Class not found');

    DB::table('datatable_columns')
        ->where('datatable', $request->tableId)
        ->where('user_id', Auth::id())
        ->delete();

    $instance = new $request->className($request);

    collect($instance->columns())
        ->map(fn ($column) => $column instanceof Column ? $column : Column::make($column))
        ->reject(fn ($column) => in_array($column->getData(), $request->columns ?? []))
        ->values()
        ->each(fn ($column) => DB::table('datatable_columns')->updateOrInsert([
            'datatable' => $request->tableId,
            'column' => $column->getData(),
            'user_id' => Auth::id(),
        ]));

    return response()->json([
        'message' => 'Columns updated successfully',
    ]);
})->middleware(['web', 'auth'])->name('datatable.columns');

Route::post('/datatable/reorder', function (Request $request) {
    $request->validate([
        'tableId' => 'required|string',
        'className' => 'required|string',
        'columns' => 'required|array',
    ]);

    abort_unless(class_exists($request->className), 404, 'Class not found');

    DB::table('datatable_column_orders')
        ->where('datatable', $request->tableId)
        ->where('user_id', Auth::id())
        ->delete();

    $weight = 0;
    foreach ($request->columns as $columnDataName) {
        DB::table('datatable_column_orders')->insert([
            'datatable' => $request->tableId,
            'column' => $columnDataName,
            'weight' => $weight++,
            'user_id' => Auth::id(),
        ]);
    }

    return response()->json([
        'message' => 'Column order saved successfully',
    ]);
})->middleware(['web', 'auth'])->name('datatable.reorder');
