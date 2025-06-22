<?php

namespace App\Http\Controllers\Api\Backend;

use Exception;
use App\Helpers\Helper;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Interaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;


class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth('api')->user();
        $clients = Client::query()->with('category', 'user')->where('user_id', $user->id)->orderBy('id', 'desc')->get();

        $data = [
            "clients" => $clients
        ];
          
        return response()->json([
            'code' => 200,
            'status' => 't-success',
            'message' => 'Your action was successful!',
            'data' => $data
        ], 200);
            
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'             => 'required|string|max:100',
            'email'            => 'required|string|max:255',
            'phone'            => 'required|string|max:20',
            'company'          => 'required|string|max:50',
            'address'          => 'nullable|string|max:1000',
            'note'             => 'nullable|string|max:1000',
            'category_id'      => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return Helper::jsonResponse(false, 'Validation failed', 422, $validator->errors());
        }

        try {
            $data = $validator->validated();
            
            $client = new Client();
            
            $client->user_id = auth('api')->user()->id;
            
            $client->name = $data['name'];
            $client->slug = Helper::makeSlug(Client::class, $data['name']);
            $client->category_id = $data['category_id'];
            $client->email = $data['email'];
            $client->phone = $data['phone'];
            $client->company = $data['company'];
            $client->address = $data['address'] ?? null;
            $client->note = $data['note'] ?? null;
            $client->save();

            return response()->json([
                'code' => 200,
                'status' => 't-success',
                'message' => 'Your action was successful!',
                'data' => $client
            ], 200);

        } catch (Exception $e) {

            return response()->json([
                'code' => 500,
                'status' => 't-error',
                'message' => 'Your action was not successful!',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = auth('api')->user();

        $client = Client::with(['category', 'user'])->where('user_id', $user->id)->where('id', $id)->first();
        
        $data = [
            'client' => $client
        ];

        return response()->json([
            'code' => 200,
            'status' => 't-success',
            'message' => 'Your action was successful!',
            'data' => $data
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'             => 'nullable|string|max:100',
            'email'            => 'nullable|string|max:255',
            'phone'            => 'nullable|string|max:20',
            'company'          => 'nullable|string|max:50',
            'address'          => 'nullable|string|max:1000',
            'note'             => 'nullable|string|max:1000',
            'category_id'      => 'nullable|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return Helper::jsonResponse(false, 'Validation failed', 422, $validator->errors());
        }

        try {
            $data = $validator->validated();

            $client = Client::findOrFail($id);

            $client->user_id = auth('web')->user()->id;

            $client->name = $data['name'] ?? $client->name;
            $client->category_id = $data['category_id'] ?? $client->category_id;
            $client->email = $data['email'] ?? $client->email;
            $client->phone = $data['phone'] ?? $client->phone;
            $client->company = $data['company'] ?? $client->company;
            $client->address = $data['address'] ?? $client->address;
            $client->note = $data['note'] ?? $client->note;
            $client->save();

            return response()->json([
                'code' => 200,
                'status' => 't-success',
                'message' => 'Your action was successful!',
                'data' => $client
            ], 200);

        } catch (Exception $e) {

            return response()->json([
                'code' => 200,
                'status' => 't-error',
                'message' => $e->getMessage(),
            ], 200);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            $data = Client::findOrFail($id);

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
        $data = Client::findOrFail($id);
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

    public function interactionsIndex($client_id)
    {
        $interactions = Interaction::query()->with('type')->where('client_id', $client_id)->orderBy('id', 'desc')->get();

        $data = [
            "interactions" => $interactions
        ];
          
        return response()->json([
            'code' => 200,
            'status' => 't-success',
            'message' => 'Your action was successful!',
            'data' => $data
        ], 200);
            
    }

    public function interactionsStore(Request $request, $client_id)
    {
        $client_id = (int)$client_id;

        $validator = Validator::make($request->all(), [
            'subject'       => 'required|string|max:255',
            'note'          => 'required|string|max:1000',
            'type_id'       => 'required|exists:types,id',
        ]);

        if ($validator->fails()) {
            return Helper::jsonResponse(false, 'Validation failed', 422, $validator->errors());
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

            return response()->json([
                'code' => 200,
                'status' => 't-success',
                'message' => 'Your action was successful!',
                'data' => $interaction
            ], 200);

        } catch (Exception $e) {

            return response()->json([
                'code' => 500,
                'status' => 't-error',
                'message' => 'Your action was successful!',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}
