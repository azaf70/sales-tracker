<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class BusinessController extends Controller
{
    public function index(): Response
    {
        $user = Auth::user();
        
        $businesses = Business::with(['creator', 'members.user'])
            ->whereHas('members', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orWhere('created_by', $user->id)
            ->get();

        return Inertia::render('Businesses/Index', [
            'businesses' => $businesses,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Businesses/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $business = Business::create([
            'name' => $request->name,
            'description' => $request->description,
            'created_by' => Auth::id(),
        ]);

        // Add creator as first member with 100% ownership
        BusinessMember::create([
            'business_id' => $business->id,
            'user_id' => Auth::id(),
            'ownership_percentage' => 100.00,
            'invited_by' => null,
        ]);

        return redirect()->route('businesses.show', $business)
            ->with('success', 'Business created successfully!');
    }

    public function show(Business $business): Response
    {
        $business->load([
            'creator', 
            'members.user', 
            'productBatches', 
            'investments.user', 
            'investments.productBatch',
            'sales.productBatch'
        ]);

        // Calculate total investments
        $totalInvestments = $business->investments->sum('amount');
        
        // Calculate total sales revenue
        $totalRevenue = $business->sales->sum('sale_price');
        
        // Calculate total profit (simplified - you might want more complex logic)
        $totalCost = $business->productBatches->sum('total_cost');
        $totalProfit = $totalRevenue - $totalCost;

        // Get member profits
        $memberProfits = $business->members->map(function ($member) use ($totalProfit) {
            return [
                'user' => $member->user,
                'ownership_percentage' => $member->ownership_percentage,
                'profit_share' => ($totalProfit * $member->ownership_percentage) / 100,
            ];
        });

        return Inertia::render('Businesses/Show', [
            'business' => $business,
            'totalInvestments' => $totalInvestments,
            'totalRevenue' => $totalRevenue,
            'totalProfit' => $totalProfit,
            'memberProfits' => $memberProfits,
        ]);
    }

    public function edit(Business $business): Response
    {
        $business->load(['members.user']);
        
        return Inertia::render('Businesses/Edit', [
            'business' => $business,
        ]);
    }

    public function update(Request $request, Business $business): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $business->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('businesses.show', $business)
            ->with('success', 'Business updated successfully!');
    }

    public function destroy(Business $business): RedirectResponse
    {
        $business->delete();

        return redirect()->route('businesses.index')
            ->with('success', 'Business deleted successfully!');
    }

    public function inviteMember(Request $request, Business $business): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'ownership_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $user = User::where('email', $request->email)->first();
        
        // Check if user is already a member
        if ($business->members()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'User is already a member of this business.');
        }

        // Check if total ownership percentage doesn't exceed 100%
        $currentTotal = $business->members->sum('ownership_percentage');
        if ($currentTotal + $request->ownership_percentage > 100) {
            return back()->with('error', 'Total ownership percentage cannot exceed 100%.');
        }

        BusinessMember::create([
            'business_id' => $business->id,
            'user_id' => $user->id,
            'ownership_percentage' => $request->ownership_percentage,
            'invited_by' => Auth::id(),
        ]);

        return back()->with('success', 'Member invited successfully!');
    }

    public function removeMember(Business $business, BusinessMember $member): RedirectResponse
    {
        $member->delete();

        return back()->with('success', 'Member removed successfully!');
    }

    public function calculateProfits(Business $business): RedirectResponse
    {
        // This is a simplified profit calculation (all amounts in UK Pounds £)
        // You might want to implement more complex logic based on your requirements
        
        $totalRevenue = $business->sales->sum('sale_price');
        $totalCost = $business->productBatches->sum('total_cost');
        $totalProfit = $totalRevenue - $totalCost;

        // Update or create profit record
        $business->profits()->updateOrCreate(
            ['product_batch_id' => null], // For overall business profit
            [
                'total_revenue' => $totalRevenue,
                'total_profit' => $totalProfit,
                'calculated_at' => now(),
            ]
        );

        return back()->with('success', 'Profits calculated successfully!');
    }
}
