<?php
namespace App\Http\Controllers;


use App\Models\TrainingNote;
use App\Models\TrainingSession;
use App\Models\Attachment;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NotesController extends Controller
{
    // Wyświetlanie listy notatek (z paginacją)
    public function index()
{
    // Pobierz wszystkie sesje użytkownika i pogrupuj je według system_id
    $userId = Auth::id();
    $sessions = TrainingSession::where('user_id', $userId)
                ->where('started', 1)
                ->where('completed',1)
                ->with('trainingSystem') // Załaduj powiązane systemy treningowe
                ->get()
                ->groupBy('system_id');

    return view('notes.index', compact('sessions'));
}

    // Formularz tworzenia nowej notatki
    public function create(Request $request)
    {
    $session_id = $request->query('session_id'); 
    return view('notes.create', ['session_id' => $session_id]);
    }

    // Zapisanie nowej notatki w bazie danych
    public function store(Request $request)
{
    $request->validate([
        'session_id' => 'required|exists:training_sessions,id',
        'content' => 'required',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048'
    ]);

    // Tworzenie nowej notatki
    $note = new TrainingNote([
        'session_id' => $request->session_id,
        'user_id' => Auth::id(),
        'content' => $request->content,
    ]);

    $note->save();

    // Aktualizacja pola note_id w tabeli training_sessions
    $session = TrainingSession::find($request->session_id);
    if ($session) {
        $session->note_id = $note->id;
        $session->save();
    }

    // Aktualizacja załączników z tej samej sesji HTTP
    if ($request->hasFile('image')) {
        // Zapisz obraz w publicznym folderze 'images'
        $file = $request->file('image');
        $path = $file->store('images', 'public');

        // Utwórz lub zaktualizuj załącznik
        $attachment = Attachment::updateOrCreate(
            ['note_id' => $note->id],
            [
                'session_id' => $request->session_id,
                'file_path' => $path
            ]
        );
    }

    return redirect()->route('notes.index')
        ->with('success', 'Notatka została zapisana.');
}


    // Wyświetlanie szczegółów notatki
    public function show($id)
    {
        $note = TrainingNote::findOrFail($id);
        $attachment = Attachment::where('note_id',$note->id)
            ->first();
        $session = TrainingSession::where('note_id',$note->id)
            ->first();
        return view('notes.show', compact('note','attachment','session'));
    }

    // Formularz edycji istniejącej notatki
    public function edit($id)
    {
        $note = TrainingNote::where('id',$id)
            ->first();
        $session_id = $note->session_id;
        return view('notes.edit', compact('note', 'session_id'));
    }

    // Aktualizacja notatki w bazie danych
    public function update(Request $request, $id)
    {
        $request->validate([
            'session_id' => 'required|exists:training_sessions,id',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $userId = Auth::id();

        // Znajdź notatkę
        $note = TrainingNote::findOrFail($id);
        $note->user_id = $userId;
        $note->session_id = $request->session_id;
        $note->content = $request->content;
        $note->save();

        // Obsługuje nowy obraz
        if ($request->hasFile('image')) {
            // Zapisz obraz w publicznym folderze 'images'
            $file = $request->file('image');
            $path = $file->store('images', 'public');

            // Utwórz lub zaktualizuj załącznik
            $attachment = Attachment::updateOrCreate(
                ['note_id' => $note->id],
                [
                    'session_id' => $request->session_id,
                    'file_path' => $path
                ]
            );
        }

        return redirect()->route('notes.index')
            ->with('success', 'Notatka została zaktualizowana.');
    }

    // Usuwanie notatki

    public function destroy($id)
    {
        // Znajdź notatkę po jej ID
        $note = TrainingNote::findOrFail($id);
    
        // Znajdź wszystkie załączniki związane z tą notatką
        $attachments = Attachment::where('note_id', $note->id)->get();
    
        // Usuń każdy załącznik i jego plik z systemu plików
        foreach ($attachments as $attachment) {
            // Usuń plik z serwera
            Storage::disk('public')->delete($attachment->file_path);
    
            // Usuń rekord załącznika z bazy danych
            $attachment->delete();
        }
    
        // Usuń notatkę
        $note->delete();
    
        // Przekierowanie po usunięciu
        return redirect()->route('notes.index')
            ->with('success', 'Notatka i wszystkie powiązane załączniki zostały usunięte.');
    }
    
    public function destroyAttachment($id)
    {
        // Znajdź załącznik
        $attachment = Attachment::findOrFail($id);

        // Usuń plik z systemu plików
        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        // Usuń rekord załącznika z bazy danych
        $attachment->delete();

        // Zwróć odpowiedź JSON z sukcesem
        return response()->json([
            'success' => true
        ]);
    }
    

}