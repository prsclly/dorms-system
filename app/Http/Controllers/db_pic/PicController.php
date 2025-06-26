<?php

namespace App\Http\Controllers\db_pic;
use App\Http\Controllers\Controller;
use App\Models\Pic;
use Illuminate\Http\Request;

class PicController extends Controller
{
    public function manage()
    {
        $pics = Pic::all();
        return view('content.db_pic.pic', compact('pics'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'nullable|email|unique:pics,email',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
        ]);

        Pic::create($request->all());
        return redirect()->route('pic.manage')->with('success', 'New data added successfully.');
    }

    public function update(Request $request, $id)
    {
        $pic = Pic::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'nullable|email|unique:pics,email,' . $pic->id,
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
        ]);

        $pic->update($request->all());
        return redirect()->route('pic.manage')->with('success', 'PIC data successfully updated.');
    }

    public function destroy($id)
    {
        Pic::destroy($id);
        return redirect()->route('pic.manage')->with('success', 'PIC data sucessfully deleted.');
    }
}
