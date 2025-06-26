<?php

namespace App\Http\Controllers\meal_menu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Meal; // Pastikan model Meal sesuai
use App\Models\Pic;

class CrudMenu extends Controller
{
    public function edit($id)
    {
        $meal = Meal::findOrFail($id);
        $pics = Pic::all(); // Untuk dropdown PIC kalau perlu

        return view('meal_menu.edit', compact('meal', 'pics'));
    }

    public function update(Request $request, $id)
    {
        $meal = Meal::findOrFail($id);
        $meal->update($request->only(['meal_type', 'time', 'menu_description', 'pic_id']));

        // Tetap stay di week yang sedang difilter
        return redirect()->route('menu_list', ['week' => $request->query('week')])
                         ->with('success', 'Data updated successfully!');
    }


    public function destroy($id)
    {
        $meal = Meal::findOrFail($id);
        $meal->delete();

        return redirect()->back()->with('success', 'Meal entry deleted successfully.');
    }

    public function storeBulk(Request $request)
{
    $request->validate([
        'date' => 'required|date',
        'meals.*.menu_description' => 'required|string',
        'meals.*.pic_id' => 'required|exists:pics,id',
    ]);

    $date = $request->input('date');

    // Check if any meals already exist for that date
    $existingMealsCount = Meal::where('date', $date)->count();

    if ($existingMealsCount > 0) {
      return redirect()->back()
      ->withInput()
      ->with('error', 'An entry for this date has already been made.');
    }

    foreach ($request->input('meals') as $meal) {
        Meal::create([
            'date'             => $date,
            'meal_type'        => $meal['meal_type'],
            'time'             => $meal['time'],
            'menu_description' => $meal['menu_description'],
            'pic_id'           => $meal['pic_id'],
        ]);
    }

    // redirect tetap ke minggu yang sedang dibuka, jika ada
    $weekParam = $request->query('week')
        ?? \Carbon\Carbon::parse($date)->startOfWeek()->format('o-\WW');

    return redirect()->route('menu_list', ['week' => $weekParam])
                     ->with('success', 'New meal entries added!');
}

public function storeSingle(Request $request)
{
    $validated = $request->validate([
        'date' => 'required|date',
        'meal_type' => 'required|string',
        'time' => 'required|string',
        'menu_description' => 'required|string',
        'pic_id' => 'required|exists:pics,id',
    ]);

    // Cek apakah meal dengan tanggal dan meal_type yang sama sudah ada
    $existingMeal = Meal::where('date', $validated['date'])
                        ->where('meal_type', $validated['meal_type'])
                        ->first();

    if ($existingMeal) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'An entry for this meal and date has already been made.');
    }

    Meal::create($validated);

    return redirect()->route('menu_list', ['week' => \Carbon\Carbon::parse($validated['date'])->format('o-\WW')])
        ->with('success', 'Meal entry added successfully!');
}

}
