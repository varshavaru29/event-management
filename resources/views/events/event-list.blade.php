@if(count($events) > 0)
<div class="grid grid-cols-1 gap-8 mt-8 md:mt-16 md:grid-cols-2">
    @foreach ($events as $event)
        <div class="lg:flex bg-slate-100 rounded-md">
            <img class="object-cover w-full h-56 rounded-lg lg:w-64"
                src="{{ asset('/storage/' . $event->image) }}" alt="{{ $event->title }}">

            <div class="flex flex-col justify-between py-6 lg:mx-6">
                <a href="{{ route('eventShow', Crypt::encrypt($event->slug)) }}"
                    class="text-xl font-semibold text-gray-800 hover:underline dark:text-white ">
                    {{ $event->title }}
                </a>
                <span class="text-sm text-gray dark:text-gray-300">
                    <p>{{$event->start_date->format('d-m-y')}} To {{$event->end_date->format('d-m-y')}}</p>
                </span>
                <span
                    class="text-sm text-white dark:text-gray-300 bg-indigo-400 rounded-md p-2">{{ $event->location }}</span>
            </div>
        </div>
    @endforeach
</div>
@else
<div class="text-center">
    <p class="text-center"> No Record Found </p>
</div>
@endif
{{ $events->links() }}
