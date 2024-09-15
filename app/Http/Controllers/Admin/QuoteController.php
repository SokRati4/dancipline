<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index()
    {
        $quotes = Quote::all();
        return view('admin.quotes.index', compact('quotes'));
    }

    public function create()
    {
        return view('admin.quotes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'quote' => 'required|string',
            'author' => 'required|string',
        ]);

        Quote::create($request->all());

        return redirect()->route('admin.quotes.index')->with('success', 'Quote created successfully.');
    }

    public function edit($id)
    {
        $quote = Quote::findOrFail($id);
        return view('admin.quotes.edit', compact('quote'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quote' => 'required|string',
            'author' => 'required|string',
        ]);

        $quote = Quote::findOrFail($id);
        $quote->update($request->all());

        return redirect()->route('admin.quotes.index')->with('success', 'Quote updated successfully.');
    }

    public function destroy($id)
    {
        $quote = Quote::findOrFail($id);
        $quote->delete();

        return redirect()->route('admin.quotes.index')->with('success', 'Quote deleted successfully.');
    }
}
