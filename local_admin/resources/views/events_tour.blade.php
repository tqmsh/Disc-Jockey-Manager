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
            if (typeof tour !== 'undefined') {
                let tour =""
            }
            tour = new Shepherd.Tour({
                defaultStepOptions: {
                    classes: 'shadow-md bg-purple-dark', // a separate CSS file
                    scrollTo: true
                }
            });

            tour.addStep({
                id: 'reportabug',
                title: 'ReTe',
                text: 'Here is a place you can report bugs. We are in beta right now and want feedback to refine the system..',
                attachTo: { element: 'a[href="http://127.0.0.1:8000/admin/events/create"', on: 'bottom' },
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
