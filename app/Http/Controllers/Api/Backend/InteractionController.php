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
