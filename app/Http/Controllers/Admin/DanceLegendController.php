<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DanceLegend;
use Illuminate\Http\Request;

class DanceLegendController extends Controller
{
    public function index()
    {
        $legends = DanceLegend::all();
        return view('admin.dance_legends.index', compact('legends'));
    }

    public function create()
    {
        return view('admin.dance_legends.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'partner1_name' => 'required|string|max:255',
            'partner2_name' => 'required|string|max:255',
            'best_results' => 'nullable|string',
            'known_for' => 'nullable|string',
            'bio' => 'nullable|string',
            'videos' => 'nullable|string',
        ]);

        DanceLegend::create($request->all());

        return redirect()->route('admin.dance_legends.index')->with('success', 'Dance Legend created successfully.');
    }

    public function edit($id)
    {
        $legend = DanceLegend::findOrFail($id);
        return view('admin.dance_legends.edit', compact('legend'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'partner1_name' => 'required|string|max:255',
            'partner2_name' => 'required|string|max:255',
            'best_results' => 'nullable|string',
            'known_for' => 'nullable|string',
            'bio' => 'nullable|string',
            'videos' => 'nullable|string',
        ]);

        $legend = DanceLegend::findOrFail($id);
        $legend->update($request->all());

        return redirect()->route('admin.dance_legends.index')->with('success', 'Dance Legend updated successfully.');
    }

    public function destroy($id)
    {
        $legend = DanceLegend::findOrFail($id);
        $legend->delete();

        return redirect()->route('admin.dance_legends.index')->with('success', 'Dance Legend deleted successfully.');
    }
}
