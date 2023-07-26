<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Http\Resources\ExpenseCollection;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use App\Notifications\ExpenseRegistered;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{

    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(Expense::class, 'expense');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $expenses = Expense::where('user_id', '=', $request->user()->id)
            ->paginate(perPage: $request->perPage ?? 10);

        return new ExpenseCollection($expenses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpenseRequest $request)
    {
        $expense = Expense::create([
            'value' => $request->value,
            'date' => $request->date,
            'description' => trim($request->description),
            'user_id' => $request->user()->id // or $request->user_id ?
        ]);

        $expenseResource = new ExpenseResource($expense);

        $request->user()->notify(new ExpenseRegistered());

        return response([$expenseResource], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense): ExpenseResource
    {
        return new ExpenseResource($expense);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        $message = "Nothing to update.";
        $attributes = [
            'description' => trim($request->description),
            'value' => $request->value,
            'date' => $request->date,
        ];

        $expense->fill($attributes);

        if ($expense->isDirty()) {
            $message = "Expense updated successfully";
            $expense->save();
        }
        $resource = new ExpenseResource($expense);

        return response(['message' => $message, $resource]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();
        $expense->save();
        return response(['message' => 'Expense deleted successfully']);
    }
}
