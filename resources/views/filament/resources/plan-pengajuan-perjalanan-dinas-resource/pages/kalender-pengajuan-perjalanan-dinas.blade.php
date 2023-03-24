<x-filament::page>
    <div id="calendar"></div>
</x-filament::page>
@push('scripts')
    <!-- Add jQuery library (required) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>

    <!-- Add the evo-calendar.js for.. obviously, functionality! -->
    <script src="https://cdn.jsdelivr.net/npm/evo-calendar@1.1.2/evo-calendar/js/evo-calendar.min.js"></script>

    <script>
        let datas = @js($this->data());

        $("#calendar").evoCalendar({
            theme: 'Royal Navy',
            eventHeaderFormat: 'd MM yyyy',
            todayHighlight: true,
            calendarEvents: datas
        });
    </script>
@endpush
