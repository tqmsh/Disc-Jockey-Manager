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
        <script type='text/javascript'>
            var tour = new Shepherd.Tour({
                defaultStepOptions: {
                    classes: 'shadow-md bg-purple-dark', // a separate CSS file
                    scrollTo: true
                }
            });
        </script>

    @foreach ($elements as $el)
        <script type='text/javascript'>
            tour.addStep({
                id: '{{$el['order_element']}}',
                title: '{{$el['title']}}',
                text: '{{$el['description']}}',
                attachTo: { element: '{{$el['element']}}', on: 'bottom' },
                buttons: [
                    { text: 'Next', action: tour.next },
                    { text: 'End', action: tour.cancel },
                ]
            });

        </script>
            @endforeach

            @endpush
</head>
<body>

</body>
