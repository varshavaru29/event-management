<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('New Event') }}
            </h2>
        </div>
    </x-slot>
    @if(session('success'))
        <div class="alert alert-success bg-red-500 text-red p-4 rounded-md shadow-lg mb-4 flex items-center justify-between">
            <div>
                <strong>Success:</strong> {{ session('success') }}
            </div>
            <button onclick="this.parentElement.style.display='none'" class="text-success focus:outline-none">
                &times;
            </button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger bg-red-500 text-red p-4 rounded-md shadow-lg mb-4 flex items-center justify-between">
            <div>
                <strong>Error:</strong> {{ session('error') }}
            </div>
            <button onclick="this.parentElement.style.display='none'" class="text-red focus:outline-none">
                &times;
            </button>
        </div>
    @endif
    @if ($errors->any())
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 p-4 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('events.store') }}" x-data="{


            }" enctype="multipart/form-data"
                class="p-4 bg-white dark:bg-slate-800 rounded-md">
                @csrf
                <div class="grid gap-6 mb-6 md:grid-cols-2">
                    <div>
                        <label for="title"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Title</label>
                        <input type="text" id="title" name="title"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Laravel event">
                        @error('title')
                            <div class="text-sm text-red-400">{{ $message }}</div>
                        @enderror
                    </div>


                    <div>
                        <label for="address"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address</label>
                        <input type="text" id="location" name="location"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Laravel event">
                        @error('location')
                            <div class="text-sm text-red-400">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            for="file_input">Upload file</label>
                        <input
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                            id="file_input" type="file" name="image">
                        @error('image')
                            <div class="text-sm text-red-400">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="start_date"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start Date</label>
                        <input type="datetime-local" id="start_date" name="start_date"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Laravel event">
                        @error('start_date')
                            <div class="text-sm text-red-400">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="end_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End
                            Date</label>
                        <input type="datetime-local" id="end_date" name="end_date"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Laravel event">
                        @error('end_date')
                            <div class="text-sm text-red-400">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="description"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                        <textarea id="description" name="description" rows="4"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Write your thoughts here..."></textarea>
                        @error('description')
                            <div class="text-sm text-red-400">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div>
                    <h3 class="mb-4 font-semibold text-gray-900 dark:text-white">Tickets</h3>
                </div>
                <div>
                    @foreach ($ticketTypes as $index => $ticketType)
                        <div class="ticket" data-index="{{ $index }}">
                            <h3 class="mt-4 font-semibold text-gray-900 dark:text-white">{{$ticketType?->name}}</h3>
                            <div class="items-center w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg sm:flex dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                                <div class="flex items-center pl-3">
                                    <input type="hidden" name="ticket_types[{{ $index }}][id]" value="{{$ticketType->id}}">
                                </div>
                                <div class="flex items-center pl-3 gap-2">
                                    <label for="text"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Price</label>
                                    <input
                                        type="text"
                                        name="ticket_types[{{ $index }}][price]"
                                        x-data="{ price: '', isValid: true }"
                                        x-model="price"
                                        @input="
                                            price = price.replace(/[^0-9.]/g, ''); // Allow only numbers and periods
                                            isValid = /^\d*(\.\d{0,2})?$/.test(price); // Validate numeric with max 2 decimals
                                        "
                                        :class="isValid ? 'border-gray-300' : 'border-red-500'"
                                        class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        />
                                    <div x-show="!isValid" class="text-sm text-red-400">Invalid price format. Use numbers like 10 or 10.99.</div>
                                    @error('ticket_types[{{ $index }}][price]')
                                        <div class="text-sm text-red-400">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="flex items-center pl-3" x-data="{
                                quantity: 1,
                                }">
                                    <label for="quantity"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Quantity</label>
                                    <input type="number" name="ticket_types[{{ $index }}][quantity]" x-model.number="quantity" @input="if (quantity < 1) quantity = 1;" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    @error('ticket_types[{{ $index }}][quantity]')
                                        <div class="text-sm text-red-400">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="flex items-center pl-3">
                                    <label for="sale_starts_at"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sale Starts At</label>
                                    <input type="datetime-local" id="ticket_types[{{ $index }}][sale_starts_at]" name="ticket_types[{{ $index }}][sale_starts_at]"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Laravel event">
                                    @error('ticket_types[{{ $index }}][sale_starts_at]')
                                        <div class="text-sm text-red-400">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="flex items-center pl-3">
                                    <label for="ticket_types[{{ $index }}][sale_ends_at]"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sale Ends At</label>
                                    <input type="datetime-local" id="ticket_types[{{ $index }}][sale_ends_at]" name="ticket_types[{{ $index }}][sale_ends_at]"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Laravel event">
                                    @error('ticket_types[{{ $index }}][sale_ends_at]')
                                        <div class="text-sm text-red-400">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    @endforeach
                <div>
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Create</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
