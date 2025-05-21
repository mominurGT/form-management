<?php

namespace App\Http\Controllers;

use App\Models\FormConfiguration;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Inertia\Response;

class FormConfigurationController extends Controller
{
    public function index()
    {
        return Inertia::render('Configure');
    }

    public function store(Request $request)
    {
        $jsonData = null;

        if ($request->hasFile('jsonFile')) {
            $file = $request->file('jsonFile');

            $validator = Validator::make(
                ['jsonFile' => $file],
                ['jsonFile' => 'required|file|mimes:json|max:4096']
            );

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $jsonData = file_get_contents($file->getRealPath());
        } else {
            $jsonData = $request->getContent();
        }

        $decoded = json_decode($jsonData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Invalid JSON data'], 400);
        }

        $validator = Validator::make(
            $decoded,
            [
                'fields' => 'required|array',
                'fields.*.name' => 'required|string',
                'fields.*.type' => 'required|string',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $formConfig = FormConfiguration::create([
            'configuration' => json_encode($decoded),
        ]);

        return response()->json([
            'message' => 'Form Configuration JSON data saved successfully',
            'id' => $formConfig->id,
        ]);
    }

    public function show($id)
    {
        $formConfig = FormConfiguration::findOrFail($id);

        return Inertia::render('Show', [
            'configuration' => json_decode($formConfig->configuration, true),
        ]);
    }

    public function viewFormList(): Response
    {
        $formConfig = FormConfiguration::select('id', 'created_at')->get();

        $formConfig = $formConfig->map(function ($item) {
            return [
                'id' => $item->id,
                'form_name' => 'Form - ' . $item->id,
                'created_date' => $item->created_at->format('d F Y'),
            ];
        });

        return Inertia::render('FormList', [
            'forms' => $formConfig,
        ]);
    }

    public function editForm($id)
    {
        $formConfig = FormConfiguration::findOrFail($id);

        return Inertia::render('FormEdit', [
            'id' => $formConfig->id,
            'configuration' => json_decode($formConfig->configuration, true),
        ]);
    }


    public function updateForm(Request $request, $id)
    {
        $jsonData = $request->getContent();

        $decoded = json_decode($jsonData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return redirect()->back()->withErrors(['json' => 'Invalid JSON data']);
        }

        $validator = Validator::make(
            $decoded,
            [
                'fields' => 'required|array',
                'fields.*.name' => 'required|string',
                'fields.*.type' => 'required|string',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $formConfig = FormConfiguration::findOrFail($id);
        $formConfig->update([
            'configuration' => json_encode($decoded),
        ]);

        return redirect('/view')->with('success', 'Form Configuration updated successfully');
    }

    public function update(Request $request, $id)
    {
        $fields = $request->input('fields');

        $validator = Validator::make(
            ['fields' => $fields],
            [
                'fields' => 'required|array',
                'fields.*.name' => 'required|string',
                'fields.*.type' => 'required|string',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $formConfig = FormConfiguration::findOrFail($id);
        $formConfig->update([
            'configuration' => json_encode(['fields' => $fields]),
        ]);

        return redirect('/view')->with('success', 'Form updated successfully');
    }

    public function edit($id)
    {
        $formConfig = FormConfiguration::findOrFail($id);

        return Inertia::render('FormBuilder', [
            'id' => $formConfig->id,
            'configuration' => json_decode($formConfig->configuration, true),
        ]);
    }
}
