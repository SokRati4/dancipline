<?php
namespace App\Http\Controllers;

use App\Models\Tutorial;
use App\Models\DanceLegend;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'all'); 
    
        if ($filter == 'tutorials') {
            $tutorials = Tutorial::all();
            $dance_legends = collect();
        } elseif ($filter == 'legends') {
            $tutorials = collect(); 
            $dance_legends = DanceLegend::all();
        } else {
            $tutorials = Tutorial::all();
            $dance_legends = DanceLegend::all();
        }
    
        return view('education.index', compact('tutorials', 'dance_legends', 'filter'));
    }
    public function showTutorial($id){
        $tutorial = Tutorial::findOrFail($id);
        return view('education.showTutorial',['tutorial' => $tutorial]);

    }
    public function showLegend($id){
        $legend = DanceLegend::findOrFail($id);
        return view('education.showLegend',['legend' => $legend]);

    }

}
