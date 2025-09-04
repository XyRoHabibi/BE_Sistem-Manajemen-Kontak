<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ContactsExport;
use App\Imports\ContactsImport;

class ContactController extends Controller
{
    // List semua kontak
    public function index(Request $request)
    {
        $query = Contact::query();

        // API untuk pencarian kontak
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
        }

        return response()->json($query->with('group')->get());
    }

    // Tambah kontak
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:contacts',
            'phone' => 'nullable|string',
            'company' => 'nullable|string',
            'position' => 'nullable|string',
            'notes' => 'nullable|string',
            'group_id' => 'nullable|exists:groups,id',
        ]);

        $contact = Contact::create($data);
        return response()->json($contact, 201);
    }

    // Update kontak
    public function update(Request $request, Contact $contact)
    {
        $data = $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:contacts,email,' . $contact->id,
            'phone' => 'nullable|string',
            'company' => 'nullable|string',
            'position' => 'nullable|string',
            'notes' => 'nullable|string',
            'group_id' => 'nullable|exists:groups,id',
        ]);

        $contact->update($data);
        return response()->json($contact);
    }

    // Hapus kontak
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return response()->json(null, 204);
    }

    // Export kontak ke Excel
    public function export()
    {
        return Excel::download(new ContactsExport, 'contacts.xlsx');
    }

    // Import kontak dari Excel
    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,csv']);
        Excel::import(new ContactsImport, $request->file('file'));
        return response()->json(['message' => 'Import sukses']);
    }
}
