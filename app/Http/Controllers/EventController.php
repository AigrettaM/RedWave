<?php
// app/Http/Controllers/EventController.php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::approved()
            ->where('event_date', '>=', now()->toDateString())
            ->orderBy('event_date', 'asc')
            ->get();
            
        return view('informasi.events.index', compact('events'));
    }

    public function show(Event $event)
    {
        // Hanya tampilkan event yang approved
        if ($event->status !== 'approved') {
            abort(404);
        }
        
        return view('informasi.events.show', compact('event'));
    }

    public function create()
    {
        return view('informasi.events.create');
    }

    public function store(Request $request)
    {
        \Log::info('=== USER EVENT SUBMISSION DEBUG ===');
        \Log::info('Request has file:', [$request->hasFile('image')]);
        \Log::info('All files:', $request->allFiles());
        \Log::info('All request data:', $request->all());
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'location' => 'required|string|max:255',
            'event_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'max_participants' => 'nullable|integer|min:1',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'submitted_by' => 'required|string|max:255',
            'submitted_email' => 'required|email|max:255',
            'submitted_phone' => 'nullable|string|max:20',
        ]);

        $data = $request->all();
        $data['type'] = 'user';
        $data['status'] = 'pending';

        // Handle image upload dengan logging detail
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            
            \Log::info('File details:', [
                'name' => $image->getClientOriginalName(),
                'size' => $image->getSize(),
                'mime' => $image->getMimeType(),
                'extension' => $image->getClientOriginalExtension(),
                'is_valid' => $image->isValid(),
                'error' => $image->getError(),
                'temp_path' => $image->getPathname()
            ]);
            
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            \Log::info('Generated filename:', [$imageName]);
            
            try {
                // Sama persis dengan admin
                $storedPath = $image->storeAs('public/events', $imageName);
                \Log::info('Store result:', [$storedPath]);
                
                // Cek apakah file benar-benar ada
                $fullPath = storage_path('app/public/events/' . $imageName);
                $fileExists = file_exists($fullPath);
                \Log::info('File check:', [
                    'full_path' => $fullPath,
                    'exists' => $fileExists,
                    'size' => $fileExists ? filesize($fullPath) : 'N/A'
                ]);
                
                // Cek dengan Storage facade
                $storageExists = Storage::exists('public/events/' . $imageName);
                \Log::info('Storage check:', [$storageExists]);
                
                $data['image'] = $imageName;
                
            } catch (\Exception $e) {
                \Log::error('Upload error:', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['image' => 'Gagal mengupload gambar: ' . $e->getMessage()]);
            }
        } else {
            \Log::info('No file uploaded');
        }

        \Log::info('Data before create:', $data);
        
        try {
            $event = Event::create($data);
            
            \Log::info('Event created:', [
                'id' => $event->id,
                'title' => $event->title,
                'image' => $event->image,
                'type' => $event->type,
                'status' => $event->status
            ]);

            return redirect()->route('events.index')
                ->with('success', 'Event berhasil diajukan! Tunggu persetujuan admin untuk ditampilkan.');
                
        } catch (\Exception $e) {
            \Log::error('Event creation error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menyimpan event: ' . $e->getMessage()]);
        }
    }
}
