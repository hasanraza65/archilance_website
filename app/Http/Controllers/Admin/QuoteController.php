<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.quotes.index', [
            'items' => Quote::when($request->query('q'), fn ($x, $t) =>
                        $x->where(fn ($w) => $w->where('name', 'like', "%{$t}%")
                                               ->orWhere('email', 'like', "%{$t}%")))
                    ->latest()->paginate(25)->withQueryString(),
            'term' => $request->query('q'),
            'unread' => Quote::where('is_read', false)->count(),
        ]);
    }

    public function show(Quote $quote)
    {
        $quote->update(['is_read' => true]);

        return view('admin.quotes.show', ['item' => $quote]);
    }

    public function destroy(Quote $quote)
    {
        $quote->delete();

        return redirect()->route('admin.quotes.index')->with('status', 'Quote deleted.');
    }
}
