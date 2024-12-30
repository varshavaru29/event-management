<x-main-layout>

    <!-- component -->
    <section class="bg-white dark:bg-gray-900">
            <div class="container px-6 py-10 mx-auto">
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
            <h1 class="text-3xl font-semibold text-gray-800 capitalize lg:text-4xl dark:text-white">All Events</h1>
            <div class="flex space-x-4 mt-6">
                <!-- Search Input -->
                <input
                    type="text"
                    id="search-input"
                    placeholder="Search Events..."
                    class="px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 w-64"
                    autocomplete="off"
                />

                <!-- Date Picker -->
                <input
                    type="date"
                    id="date-picker"
                    class="px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                />
            </div>
            <div id='events-list'>
                @include('events.event-list')
            </div>
        </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            let debounceTimeout;

            // Handle Search Input
            $('#search-input').on('keyup', function () {
                clearTimeout(debounceTimeout);
                debounceTimeout = setTimeout(function () {
                    let query = $('#search-input').val();
                    let date = $('#date-picker').val();

                    $.ajax({
                        url: "{{ route('search') }}",  // Add your Laravel search route
                        method: 'GET',
                        data: { query: query, date: date },
                        success: function (response) {
                            $('#events-list').html(response);
                        }
                    });
                }, 500);  // Delay of 500ms to handle the debounce
            });

            // Handle Date Picker Input
            $('#date-picker').on('change', function () {
                let date = $(this).val();
                let query = $('#search-input').val();

                $.ajax({
                    url: "{{ route('search') }}",  // Add your Laravel search route
                    method: 'GET',
                    data: { query: query, date: date },
                    success: function (response) {
                        $('#events-list').html(response);
                    }
                });
            });
        });
    </script>
</x-main-layout>
