<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EnquiryController extends Controller
{
    public function index(Request $request)
    {
        $items = Enquiry::when($request->query('q'), fn ($q, $t) =>
                    $q->where(fn ($w) => $w->where('name', 'like', "%{$t}%")
                                           ->orWhere('email', 'like', "%{$t}%")
                                           ->orWhere('message', 'like', "%{$t}%")))
                ->when($request->query('unread'), fn ($q) => $q->where('is_read', false))
                ->latest()->paginate(25)->withQueryString();

        return view('admin.enquiries.index', [
            'items' => $items,
            'term' => $request->query('q'),
            'unreadOnly' => (bool) $request->query('unread'),
            'unread' => Enquiry::where('is_read', false)->count(),
        ]);
    }

    public function show(Enquiry $enquiry)
    {
        $enquiry->update(['is_read' => true]);

        return view('admin.enquiries.show', ['item' => $enquiry]);
    }

    public function destroy(Enquiry $enquiry)
    {
        $enquiry->delete();

        return redirect()->route('admin.enquiries.index')->with('status', 'Enquiry deleted.');
    }

    public function export(): StreamedResponse
    {
        $columns = ['id', 'name', 'email', 'phone', 'company', 'service', 'budget',
                    'timeline', 'source', 'nda', 'message', 'created_at'];

        return response()->streamDownload(function () use ($columns) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $columns);
            Enquiry::orderBy('id')->chunk(200, function ($rows) use ($out, $columns) {
                foreach ($rows as $row) {
                    fputcsv($out, array_map(fn ($c) => $row->{$c}, $columns));
                }
            });
            fclose($out);
        }, 'enquiries-' . now()->format('Y-m-d') . '.csv', ['Content-Type' => 'text/csv']);
    }
}
