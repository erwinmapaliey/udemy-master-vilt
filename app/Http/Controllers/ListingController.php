<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function index()
    {
        $data['listings'] = Listing::all();

        return inertia('Listing/IndexListing', $data);
    }

    public function create()
    {
        return inertia('Listing/CreateListing');
    }

    public function store(Request $request)
    {
        Listing::create(
            $request->validate([
                'beds' => 'required|integer|min:0|max:20',
                'baths' => 'required|integer|min:0|max:20',
                'area' => 'required|integer|min:15|max:1500',
                'city' => 'required',
                'code' => 'required',
                'street' => 'required',
                'street_number' => 'required|min:1|max:1000',
                'price' => 'required|integer|min:1|max:20000000',
            ])
        );

        return redirect()->route('listing.index')->with('success', 'Listing was created!');
    }

    public function show(Listing $listing)
    {
        $data['listing'] = $listing;

        return inertia('Listing/ShowListing', $data);
    }

    public function edit(Listing $listing)
    {
        $data['listing'] = $listing;

        return inertia('Listing/EditListing', $data);
    }

    public function update(Request $request, Listing $listing)
    {
        $listing->update(
            $request->validate([
                'beds' => 'required|integer|min:0|max:20',
                'baths' => 'required|integer|min:0|max:20',
                'area' => 'required|integer|min:15|max:1500',
                'city' => 'required',
                'code' => 'required',
                'street' => 'required',
                'street_number' => 'required|min:1|max:1000',
                'price' => 'required|integer|min:1|max:20000000',
            ])
        );

        return redirect()->route('listing.index')->with('success', 'Listing was change!');
    }

    public function destroy(Listing $listing)
    {
        $listing->delete();

        return redirect()->route('listing.index')->with('success', 'Listing was deleted!');
    }
}
