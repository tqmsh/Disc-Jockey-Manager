<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            var tour = new Shepherd.Tour({
                defaultStepOptions: {
                    classes: 'shadow-md bg-purple-dark', // a separate CSS file
                    scrollTo: true
                }
            });

            tour.addStep({
                id: '1',
                title: 'Report a Bug',
                text: 'Here is a place you can report bugs. We are in beta right now and want feedback to refine the system..',
                attachTo: { element: '#reportabug', on: 'bottom' },
                buttons: [
                    { text: 'Next', action: tour.next },
                    { text: 'End', action: tour.cancel },
                ]
            });

            tour.addStep({
                id: '2',
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
                id: '3',
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
                id: '4',
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
