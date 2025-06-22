<?php

namespace App\Http\Controllers\Web\Backend;

use Exception;
use App\Models\Interaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Type;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class InteractionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $client_id)
    {
        $client_id = (int)$client_id;

        if ($request->ajax()) {
            $data = Interaction::query()->with('type')->where('client_id', $client_id)->orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('type', function ($data) {
                    return "<a href='" . route('admin.type.show', $data->type_id) . "'>" . $data->type->name . "</a>";
                })
                ->addColumn('subject', function ($data) {
                    return Str::limit($data->subject, 20);
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">

                                <a href="#" type="button" onclick="goToEdit(' . $data->id . ')" class="btn btn-primary fs-14 text-white delete-icn" title="Delete">
                                    <i class="fe fe-edit"></i>
                                </a>

                                <a href="#" type="button" onclick="goToOpen(' . $data->id . ')" class="btn btn-success fs-14 text-white delete-icn" title="Delete">
                                    <i class="fe fe-eye"></i>
                                </a>

                                <a href="#" type="button" onclick="showDeleteConfirm(' . $data->id . ')" class="btn btn-danger fs-14 text-white delete-icn" title="Delete">
                                    <i class="fe fe-trash"></i>
                                </a>
                            </div>';
                })
                ->rawColumns(['type', 'subject', 'action'])
                ->make();
        }
        return view("backend.layouts.client.interaction.index", compact('client_id'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($client_id)
    {
        $client_id = (int)$client_id;
        $types = Type::where('status', 'active')->get();
        return view('backend.layouts.client.interaction.create', compact('types', 'client_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $client_id)
    {
        $client_id = (int)$client_id;

        $validator = Validator::make($request->all(), [
            'subject'       => 'required|string|max:255',
            'note'          => 'required|string|max:1000',
            'type_id'       => 'required|exists:types,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        /* $user = auth('api')->user();
        $client = $user->clients()->find($client_id);
        if (!$client) {
            session()->put('t-error', 'You are not authorized to create interaction for this client');
            return redirect()->route('admin.client.interaction.index', $client_id);
        } */

        try {
            $data = $validator->validated();

            $interaction = new Interaction();

            $interaction->subject = $data['subject'];
            $interaction->note = $data['note'] ?? null;
            $interaction->type_id = $data['type_id'];
            $interaction->client_id = $client_id;
            $interaction->save();

            session()->put('t-success', 'interaction created successfully');
        } catch (Exception $e) {

            session()->put('t-error', $e->getMessage());
        }

        return redirect()->route('admin.client.interaction.index', $client_id)->with('t-success', 'interaction created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $interaction = Interaction::with(['type', 'client'])->where('id', $id)->first();
        return view('backend.layouts.client.interaction.show', compact('interaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(interaction $interaction, $id)
    {
        $interaction = Interaction::findOrFail($id);
        $types = Type::where('status', 'active')->get();
        return view('backend.layouts.client.interaction.edit', compact('interaction', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'subject'       => 'required|string|max:255',
            'note'          => 'nullable|string|max:1000',
            'type_id'       => 'required|exists:types,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $data = $validator->validated();

            $interaction = Interaction::findOrFail($id);

            /* $user = auth('api')->user();
            $client = $user->clients()->find($interaction->client_id);
            if (!$client) {
                session()->put('t-error', 'You are not authorized to create interaction for this client');
                return redirect()->route('admin.client.interaction.index', $interaction->client_id);
            } */

            $interaction->subject = $data['subject'];
            $interaction->note = $data['note'] ?? null;
            $interaction->type_id = $data['type_id'];
            $interaction->save();

            session()->put('t-success', 'Interaction updated successfully');
        } catch (Exception $e) {

            session()->put('t-error', 'Something went wrong');
        }

        return redirect()->route('admin.client.interaction.edit', $interaction->id)->with('t-success', 'Interaction updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            $data = Interaction::findOrFail($id);

            $data->delete();
            return response()->json([
                'status' => 't-success',
                'message' => 'Your action was successful!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 't-error',
                'message' => 'Your action was successful!'
            ]);
        }
    }

    public function status(int $id): JsonResponse
    {
        $data = Interaction::findOrFail($id);
        if (!$data) {
            return response()->json([
                'status' => 't-error',
                'message' => 'Item not found.',
            ]);
        }
        $data->status = $data->status === 'active' ? 'inactive' : 'active';
        $data->save();
        return response()->json([
            'status' => 't-success',
            'message' => 'Your action was successful!',
        ]);
    }
}
