<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time Range Toggle</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .shepherd-content {
            background: #FAF9F6;
        }
        .shepherd.tippy-popper[x-placement^="left"] .tippy-arrow{
            border-left-color: #FAF9F6;
        }

        .shepherd-button{
            color: #ffffff;
        }
    </style>
    @stack('scripts')


    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/shepherd.js@5.0.1/dist/js/shepherd.js"></script>


        <!--- Tooltip player -->
        <script type='text/javascript'>
            var linkToAdd = window.location.href.split('admin/')[0]
            linkToAdd = linkToAdd + 'admin/'

            var tour = new Shepherd.Tour({
                defaultStepOptions: {
                    classes: 'shadow-md bg-purple-dark', // a separate CSS file
                    scrollTo: true
                }
            });

            tour.addStep({
                id: 'addNewEvent',
                title: 'Add New Event',
                text: 'Here is a place where you can configure new events for your prom committee.',
                attachTo: { element: 'a[href='+'"'+linkToAdd + 'events/create'+'"'+']', on: 'bottom' },
                buttons: [
                    { text: 'Next', action: tour.next },
                    { text: 'End', action: tour.cancel },
                ]
            });

            tour.addStep({
                id: 'suggestVendor',
                title: 'Suggest Vendor',
                text: 'Here you can suggest a vendor to be added to our system for prom events',
                attachTo: { element: 'a[href='+'"'+linkToAdd + 'events/suggestVendor'+'"'+']', on: 'bottom' },
                buttons: [
                    { text: 'Next', action: tour.next },
                    { text: 'End', action: tour.cancel },
                ]
            });

            tour.addStep({
                id: 'checkDeleteBox',
                title: 'Delete Record',
                text: 'Select any events you want to delete in the table below and press this button to do so',
                attachTo: { element: 'button[formaction='+'"'+linkToAdd + 'events/deleteEvents'+'"'+']', on: 'bottom' },
                buttons: [
                    { text: 'Next', action: tour.next },
                    { text: 'End', action: tour.cancel },
                ]
            });

            tour.addStep({
                id: 'filterEvents',
                title: 'Filter Records',
                text: 'Filter existing events by country, province etc..',
                attachTo: { element: 'button[formaction='+'"'+linkToAdd + 'events/filter'+'"'+']', on: 'bottom' },
                buttons: [
                    { text: 'Next', action: tour.next },
                    { text: 'End', action: tour.cancel },
                ]
            });

            tour.addStep({
                id: 'configureEventCols',
                title: 'Configure Columns',
                text: 'Configure existing columns (see which columns you want to see)',
                attachTo: { element: '.btn.btn-sm.btn-link.dropdown-toggle.p-0.m-0', on: 'bottom' },
                buttons: [
                    { text: 'Next', action: tour.next },
                    { text: 'End', action: tour.cancel },
                ]
            });

            tour.addStep({
                id: 'AllRecords',
                title: 'See All Records',
                text: 'Here you can see all records and view students, )',
                attachTo: { element: 'th[data-column="event-name"]', on: 'bottom' },
                buttons: [
                    { text: 'Next', action: tour.next },
                    { text: 'End', action: tour.cancel },
                ]
            });



        </script>
    @endpush
</head>
<body>

</body>
