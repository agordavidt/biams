<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resources.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $resources = Resource::all();
        return view('admin.resources.index', compact('resources'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.resources.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'target_practice' => 'required|in:all,crop-farmer,animal-farmer,abattoir-operator,processor',
            'requires_payment' => 'required|boolean',
            'price' => 'required_if:requires_payment,1|numeric|min:0',
            // 'payment_option' => 'required_if:requires_payment,1|in:bank_transfer,entrasact,paystack',
            'payment_option' => 'nullable|required_if:requires_payment,1|in:bank_transfer,entrasact,paystack',
            'bank_account_name' => 'required_if:payment_option,bank_transfer|string|max:255|nullable',
            'bank_account_number' => 'required_if:payment_option,bank_transfer|string|max:255|nullable',
            'bank_name' => 'required_if:payment_option,bank_transfer|string|max:255|nullable',
            'entrasact_instruction' => 'required_if:payment_option,entrasact|string|nullable',
            'paystack_instruction' => 'required_if:payment_option,paystack|string|nullable',
            'form_fields' => 'required|json',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $formFields = json_decode($request->form_fields, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($formFields)) {
            return response()->json(['success' => false, 'errors' => ['form_fields' => 'Invalid JSON']], 422);
        }

        $resourceData = [
            'name' => $request->name,
            'description' => $request->description,
            'target_practice' => $request->target_practice,
            'requires_payment' => $request->boolean('requires_payment'),
            'price' => $request->requires_payment ? $request->price : 0,
            'form_fields' => $formFields,
            'is_active' => true,
        ];

        if ($request->requires_payment) {
            $resourceData['payment_option'] = $request->payment_option;
            if ($request->payment_option === 'bank_transfer') {
                $resourceData['bank_account_name'] = $request->bank_account_name;
                $resourceData['bank_account_number'] = $request->bank_account_number;
                $resourceData['bank_name'] = $request->bank_name;
                $resourceData['entrasact_instruction'] = null;
                $resourceData['paystack_instruction'] = null;
            } elseif ($request->payment_option === 'entrasact') {
                $resourceData['entrasact_instruction'] = $request->entrasact_instruction;
                $resourceData['bank_account_name'] = null;
                $resourceData['bank_account_number'] = null;
                $resourceData['bank_name'] = null;
                $resourceData['paystack_instruction'] = null;
            } elseif ($request->payment_option === 'paystack') {
                $resourceData['paystack_instruction'] = $request->paystack_instruction;
                $resourceData['bank_account_name'] = null;
                $resourceData['bank_account_number'] = null;
                $resourceData['bank_name'] = null;
                $resourceData['entrasact_instruction'] = null;
            }
        } else {
            $resourceData['payment_option'] = null;
            $resourceData['bank_account_name'] = null;
            $resourceData['bank_account_number'] = null;
            $resourceData['bank_name'] = null;
            $resourceData['entrasact_instruction'] = null;
            $resourceData['paystack_instruction'] = null;
        }

        $resource = Resource::create($resourceData);

        return response()->json([
            'success' => true,
            'message' => 'Resource created successfully',
            'redirect' => route('admin.resources.index'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\View\View
     */
    public function edit(Resource $resource)
    {
        return view('admin.resources.edit', compact('resource'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Resource $resource)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'target_practice' => 'required|in:all,crop-farmer,animal-farmer,abattoir-operator,processor',
            'requires_payment' => 'required|boolean',
            'price' => 'required_if:requires_payment,1|numeric|min:0',
            'payment_option' => 'required_if:requires_payment,1|in:bank_transfer,entrasact,paystack',
            'bank_account_name' => 'required_if:payment_option,bank_transfer|string|max:255|nullable',
            'bank_account_number' => 'required_if:payment_option,bank_transfer|string|max:255|nullable',
            'bank_name' => 'required_if:payment_option,bank_transfer|string|max:255|nullable',
            'entrasact_instruction' => 'required_if:payment_option,entrasact|string|nullable',
            'paystack_instruction' => 'required_if:payment_option,paystack|string|nullable',
            'form_fields' => 'required|json',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $formFields = json_decode($request->form_fields, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($formFields)) {
            return response()->json(['success' => false, 'errors' => ['form_fields' => 'Invalid JSON']], 422);
        }

        $resourceData = [
            'name' => $request->name,
            'description' => $request->description,
            'target_practice' => $request->target_practice,
            'requires_payment' => $request->boolean('requires_payment'),
            'price' => $request->requires_payment ? $request->price : 0,
            'form_fields' => $formFields,
        ];

        if ($request->requires_payment) {
            $resourceData['payment_option'] = $request->payment_option;
            if ($request->payment_option === 'bank_transfer') {
                $resourceData['bank_account_name'] = $request->bank_account_name;
                $resourceData['bank_account_number'] = $request->bank_account_number;
                $resourceData['bank_name'] = $request->bank_name;
                $resourceData['entrasact_instruction'] = null;
                $resourceData['paystack_instruction'] = null;
            } elseif ($request->payment_option === 'entrasact') {
                $resourceData['entrasact_instruction'] = $request->entrasact_instruction;
                $resourceData['bank_account_name'] = null;
                $resourceData['bank_account_number'] = null;
                $resourceData['bank_name'] = null;
                $resourceData['paystack_instruction'] = null;
            } elseif ($request->payment_option === 'paystack') {
                $resourceData['paystack_instruction'] = $request->paystack_instruction;
                $resourceData['bank_account_name'] = null;
                $resourceData['bank_account_number'] = null;
                $resourceData['bank_name'] = null;
                $resourceData['entrasact_instruction'] = null;
            }
        } else {
            $resourceData['payment_option'] = null;
            $resourceData['bank_account_name'] = null;
            $resourceData['bank_account_number'] = null;
            $resourceData['bank_name'] = null;
            $resourceData['entrasact_instruction'] = null;
            $resourceData['paystack_instruction'] = null;
        }

        $resource->update($resourceData);

        return response()->json([
            'success' => true,
            'message' => 'Resource updated successfully',
            'redirect' => route('admin.resources.index'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Resource $resource)
    {
        $resource->delete();
        return redirect()->route('admin.resources.index')
            ->with('success', 'Resource deleted successfully.');
    }
}