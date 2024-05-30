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
            let tour = new Shepherd.Tour({
                defaultStepOptions: {
                    classes: 'shadow-md bg-purple-dark', // a separate CSS file
                    scrollTo: true
                }
            });

            tour.addStep({
                id: 'reportabug',
                title: 'Report a Bug',
                text: 'Here is a place you can report bugs. We are in beta right now and want feedback to refine the system..',
                attachTo: { element: '#reportabug', on: 'bottom' },
                buttons: [
                    { text: 'Next', action: tour.next },
                    { text: 'End', action: tour.cancel },
                ]
            });

            tour.addStep({
                id: 'roadmap',
                title: 'Roadmap',
                text: 'The roadmap describes features that are currently available and those that will be available in future releases.',
                attachTo: { element: '#roadmap', on: 'left' },
                buttons: [
                    { text: 'Back', action: tour.back },
                    { text: 'Next', action: tour.next },
                    { text: 'End', action: tour.cancel },
                ]
            });

            tour.addStep({
                id: 'socials',
                title: 'Socials',
                text: 'All the social networks that prom planner belongs to and you are more than free to communicate with us on any of these.',
                attachTo: { element: '#socials', on: 'top' },
                buttons: [
                    { text: 'Back', action: tour.back },
                    { text: 'Next', action: tour.next },
                    { text: 'End', action: tour.cancel },
                ]
                //This is a sitewide notice, provides information about the prom planner.

            });

            tour.addStep({
                id: 'Sitewide Notice',
                title: 'Sitewide Notice',
                text: 'This is a sitewide notice, provides information about the prom planner.',
                attachTo: { element: '.h3.text-black.font-bold', on: 'top' },
                buttons: [
                    { text: 'Back', action: tour.back },
                    { text: 'End', action: tour.cancel },
                ]
                //This is a sitewide notice, provides information about the prom planner.

            });


        </script>
    @endpush
</head>
<body>

</body>
