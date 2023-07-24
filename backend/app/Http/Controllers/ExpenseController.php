<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Resources\ExpenseCollection;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //return ExpenseCollection()
        return Expense::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpenseRequest $request)
    {
        $expense = Expense::create([
            'value' => $request->value,
            'date' => $request->date,
            'description' => $request->description,
            'user_id' => $request->user_id
        ]);

        $expenseResource = new ExpenseResource($expense);

        return response([$expenseResource], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): ExpenseResource
    {
        return new ExpenseResource(Expense::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
