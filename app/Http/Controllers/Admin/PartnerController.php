<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::withCount('resources')->get();
        return view('admin.partners.index', compact('partners'));
    }

    public function create()
    {
        $partner = new Partner();
        return view('admin.partners.create', compact('partner'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'legal_name' => 'required|string|max:255',
            'registration_number' => 'nullable|string|max:50',
            'organization_type' => 'required|string',
            'establishment_date' => 'nullable|date',
            'contact_person_name' => 'required|string|max:255',
            'contact_person_title' => 'nullable|string|max:100',
            'contact_person_phone' => 'required|string|max:20',
            'contact_person_email' => 'required|email|max:255',
            'address' => 'required|string',
            'website' => 'nullable|url|max:255',
            'description' => 'required|string|max:500',
            'focus_areas' => 'required|array',
            'focus_areas.*' => 'string|in:' . implode(',', array_keys((new Partner)->getFocusAreaOptions())),
            'tax_identification_number' => 'nullable|string|max:50',
            'registration_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
            'bank_name' => 'nullable|string|max:100',
            'bank_account_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $partnerData = $request->except(['registration_certificate', 'is_active']);

            // Handle file upload
            if ($request->hasFile('registration_certificate')) {
                $file = $request->file('registration_certificate');
                $filename = 'certificate_' . time() . '_' . Str::slug($request->legal_name) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('partner_certificates', $filename, 'public');
                $partnerData['registration_certificate'] = $path;
            }

            // Handle checkbox for active status
            $partnerData['is_active'] = $request->has('is_active');

            Partner::create($partnerData);

            return redirect()->route('admin.partners.index')
                ->with('success', 'Partner organization added successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating partner: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Partner $partner)
    {
        $partner->load('resources');
        return view('admin.partners.show', compact('partner'));
    }

    public function edit(Partner $partner)
    {
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        $validator = Validator::make($request->all(), [
            'legal_name' => 'required|string|max:255',
            'registration_number' => 'nullable|string|max:50',
            'organization_type' => 'required|string',
            'establishment_date' => 'nullable|date',
            'contact_person_name' => 'required|string|max:255',
            'contact_person_title' => 'nullable|string|max:100',
            'contact_person_phone' => 'required|string|max:20',
            'contact_person_email' => 'required|email|max:255',
            'address' => 'required|string',
            'website' => 'nullable|url|max:255',
            'description' => 'required|string|max:500',
            'focus_areas' => 'required|array',
            'focus_areas.*' => 'string|in:' . implode(',', array_keys($partner->getFocusAreaOptions())),
            'tax_identification_number' => 'nullable|string|max:50',
            'registration_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
            'bank_name' => 'nullable|string|max:100',
            'bank_account_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $partnerData = $request->except(['registration_certificate', 'is_active']);

            // Handle file upload
            if ($request->hasFile('registration_certificate')) {
                // Delete old file if exists
                if ($partner->registration_certificate) {
                    Storage::disk('public')->delete($partner->registration_certificate);
                }

                $file = $request->file('registration_certificate');
                $filename = 'certificate_' . time() . '_' . Str::slug($request->legal_name) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('partner_certificates', $filename, 'public');
                $partnerData['registration_certificate'] = $path;
            }

            // Handle checkbox for active status
            $partnerData['is_active'] = $request->has('is_active');

            $partner->update($partnerData);

            return redirect()->route('admin.partners.index')
                ->with('success', 'Partner organization updated successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating partner: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Partner $partner)
    {
        try {
            // Check if partner has resources
            if ($partner->resources()->exists()) {
                return redirect()->back()
                    ->with('error', 'Cannot delete partner with associated resources. Please reassign or delete those resources first.');
            }

            // Delete certificate file if exists
            if ($partner->registration_certificate) {
                Storage::disk('public')->delete($partner->registration_certificate);
            }

            $partner->delete();
            return redirect()->route('admin.partners.index')
                ->with('success', 'Partner organization deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting partner: ' . $e->getMessage());
        }
    }
}