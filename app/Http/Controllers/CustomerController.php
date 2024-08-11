<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Http\Responses\ErrorResponse;
use App\Models\Customer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class CustomerController extends Controller {
    public function index(): AnonymousResourceCollection|JsonResponse {
        try {
            $customers = Customer::query()->paginate(10);

            return CustomerResource::collection($customers);
        } catch (ModelNotFoundException $e) {
            return ErrorResponse::make(__('messages.not_found'), Response::HTTP_NOT_FOUND);
        }
    }

    public function show(int $id): CustomerResource|JsonResponse {
        try {
            $customer = Customer::findOrFail($id);

            return new CustomerResource($customer);
        } catch (ModelNotFoundException $e) {
            return ErrorResponse::make(__('messages.not_found'), Response::HTTP_NOT_FOUND);
        }
    }

    public function store(StoreCustomerRequest $request): CustomerResource|JsonResponse {
        $customer = Customer::create($request->validated());

        return new CustomerResource($customer);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): CustomerResource|JsonResponse {
        try {
            $customer->update($request->validated());

            return response()->json($customer);
        } catch (ModelNotFoundException $e) {
            return ErrorResponse::make(__('messages.not_found'), Response::HTTP_NOT_FOUND);
        }
    }

    // Deletar um cliente
    public function destroy(Customer $customer): JsonResponse {
        $customer->delete();

        return response()->json(null, 204);
    }
}
