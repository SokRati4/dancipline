<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tutorial;
use Illuminate\Http\Request;

class TutorialController extends Controller
{
    public function index()
    {
        $tutorials = Tutorial::all();
        return view('admin.tutorials.index', compact('tutorials'));
    }

    public function create()
    {
        return view('admin.tutorials.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'required|string|max:255',
            'duration' => 'required|date_format:H:i:s',
            'level' => 'required|in:beginner,amateur,professional',
        ]);

        Tutorial::create($request->all());

        return redirect()->route('admin.tutorials.index')->with('success', 'Tutorial created successfully.');
    }

    public function edit($id)
    {
        $tutorial = Tutorial::findOrFail($id);
        return view('admin.tutorials.edit', compact('tutorial'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'required|string|max:255',
            'duration' => 'required|date_format:H:i:s',
            'level' => 'required|in:beginner,amateur,professional',
        ]);

        $tutorial = Tutorial::findOrFail($id);
        $tutorial->update($request->all());

        return redirect()->route('admin.tutorials.index')->with('success', 'Tutorial updated successfully.');
    }

    public function destroy($id)
    {
        $tutorial = Tutorial::findOrFail($id);
        $tutorial->delete();

        return redirect()->route('admin.tutorials.index')->with('success', 'Tutorial deleted successfully.');
    }
}
