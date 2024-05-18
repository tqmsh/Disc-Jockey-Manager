<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time Range Toggle</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    @stack('scripts')
    @push('scripts')
    <!--- Tooltip player -->
        <!--- Tooltip player -->
        <script type='text/javascript'>
            window.Tooltip||function(t,e){var o={
                url:"https://cdn.tooltip.io/static/player.js",
                key:"3cfc9c1e-65bb-489f-b8a1-9e09978b56a7",
                async:true
            };

                window.Tooltip={cs:[],_apiKey:o.key};for(
                    var r=["identify","goal","updateUserData","start","stop","refresh","show","hide","on"],
                        i={},n=0;n<r.length;n++){var a=r[n];i[a]=function(t){return function(){var e=Array.prototype.slice.call(arguments);
                    window.Tooltip.cs.push({method:t,args:e})}}(a)}window.Tooltip.API=i;var n=t.createElement(e),s=t.getElementsByTagName(e)[0];
                n.type="text/javascript",n.async=o.async,s.parentNode.insertBefore(n,s),n.src=o.url}(document,"script");
        </script>
        <!--- Tooltip player -->    <!--- Tooltip player -->
        @endpush
</head>
<body class="bg-gray-200 h-screen flex items-center justify-center">

<input type="button"
       class="button" value="Button"
       onclick="myGeeks()">

<script>
    function myGeeks() {
        document.querySelector(".button").
            onclick = function () {
            Tooltip.API.show('6644e55252149c0019169f7a');
        }
    }
</script>

<div class="flex space-x-4 mb-3">
    <div class="bg-white p-4 rounded shadow-md w-25">
        <small class="ml-1 mb-6 text-gray-600">New Schools</small>

        <!-- Toggle Buttons -->
        <div class="grid md:grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 grid-flow-row-dense gap-x-0 mb-3 mt-2 mb-2">
            <button class="toggle-btn1 w-10 h-10 flex items-center justify-center text-xs px-2 py-2 rounded-full focus:outline-none bg-gray-300" data-range="24">24hr</button>
            <button class="toggle-btn1 w-10 h-10 flex items-center justify-center text-xs px-2 py-2 rounded-full focus:outline-none bg-gray-300" data-range="7">7d</button>
            <button class="toggle-btn1 w-10 h-10 flex items-center justify-center text-xs px-2 py-2 rounded-full focus:outline-none bg-gray-300" data-range="30">30d</button>
            <button class="toggle-btn1 w-10 h-10 flex items-center justify-center text-xs px-2 py-2 rounded-full focus:outline-none bg-gray-300" data-range="90">90d</button>
        </div>

        <!-- Placeholder for Content -->
        <div class="ml-1 p-0 rounded-md text-3xl fw-light">
            <!-- Your content goes here -->
            <p id="selectedRange1">Selected Time Range: 24 Hours</p>
        </div>
    </div>

    <div class="bg-white p-4 rounded shadow-md w-25">
        <small class="ml-1 mb-6 text-gray-600">New Students</small>

        <!-- Toggle Buttons -->
        <div class="grid md:grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 grid-flow-row-dense gap-x-0 mb-3 mt-2 mb-2">
            <button class="toggle-btn2 w-10 h-10 flex items-center justify-center text-xs px-2 py-2 rounded-full focus:outline-none bg-gray-300 text-white" data-range="24">24hr</button>
            <button class="toggle-btn2 w-10 h-10 flex items-center justify-center text-xs px-2 py-2 rounded-full focus:outline-none bg-gray-300" data-range="7">7d</button>
            <button class="toggle-btn2 w-10 h-10 flex items-center justify-center text-xs px-2 py-2 rounded-full focus:outline-none bg-gray-300" data-range="30">30d</button>
            <button class="toggle-btn2 w-10 h-10 flex items-center justify-center text-xs px-2 py-2 rounded-full focus:outline-none bg-gray-300" data-range="90">90d</button>
        </div>

        <!-- Placeholder for Content -->
        <div class="ml-1 p-0 rounded-md text-3xl fw-light">
            <!-- Your content goes here -->
            <p id="selectedRange2">Selected Time Range: 22 Hours</p>
        </div>
    </div>

    <div class="bg-white p-4 rounded shadow-md w-25">
        <small class="ml-1 mb-6 text-gray-600">New Vendors</small>

        <!-- Toggle Buttons -->
        <div class="grid md:grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 grid-flow-row-dense gap-x-0 mb-3 mt-2 mb-2">
            <button class="toggle-btn3 w-10 h-10 flex items-center justify-center text-xs px-2 py-2 rounded-full focus:outline-none bg-gray-300 text-white" data-range="24">24hr</button>
            <button class="toggle-btn3 w-10 h-10 flex items-center justify-center text-xs px-2 py-2 rounded-full focus:outline-none bg-gray-300" data-range="7">7d</button>
            <button class="toggle-btn3 w-10 h-10 flex items-center justify-center text-xs px-2 py-2 rounded-full focus:outline-none bg-gray-300" data-range="30">30d</button>
            <button class="toggle-btn3 w-10 h-10 flex items-center justify-center text-xs px-2 py-2 rounded-full focus:outline-none bg-gray-300" data-range="90">90d</button>
        </div>

        <!-- Placeholder for Content -->
        <div class="ml-1 p-0 rounded-md text-3xl fw-light">
            <!-- Your content goes here -->
            <p id="selectedRange3">Selected Time Range: 20 Hours</p>
        </div>
    </div>

    <div class="bg-white p-4 rounded shadow-md w-25">
        <small class="ml-1 mb-6 text-gray-600">New Brands</small>

        <!-- Toggle Buttons -->
        <div class="grid md:grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 grid-flow-row-dense gap-x-0 mb-3 mt-2 mb-2">
            <button class="toggle-btn4 w-10 h-10 flex items-center justify-center text-xs px-2 py-2 rounded-full focus:outline-none bg-gray-300 text-white" data-range="24">24hr</button>
            <button class="toggle-btn4 w-10 h-10 flex items-center justify-center text-xs px-2 py-2 rounded-full focus:outline-none bg-gray-300" data-range="7">7d</button>
            <button class="toggle-btn4 w-10 h-10 flex items-center justify-center text-xs px-2 py-2 rounded-full focus:outline-none bg-gray-300" data-range="30">30d</button>
            <button class="toggle-btn4 w-10 h-10 flex items-center justify-center text-xs px-2 py-2 rounded-full focus:outline-none bg-gray-300" data-range="90">90d</button>
        </div>

        <!-- Placeholder for Content -->
        <div class="ml-1 p-0 rounded-md text-3xl fw-light">
            <!-- Your content goes here -->
            <p id="selectedRange4">Selected Time Range: 18 Hours</p>
        </div>
    </div>
</div>


<!-- Include JavaScript -->
@push('scripts')
    <script defer>
        if (document.readyState !== 'loading') {
            //when switching tabs or on login, document is already ready so you can call loadmetrics directly.
            loadMetrics();
        } else {
            document.addEventListener('DOMContentLoaded', function () {
                // Reloading the page, we must wait till all the DOM is loaded, to call loadmetrics.
                loadMetrics();
            });
        }
        function loadMetrics() {
            // Get all toggle buttons for each section
            const toggleButtons1 = document.querySelectorAll('.toggle-btn1');
            const toggleButtons2 = document.querySelectorAll('.toggle-btn2');
            const toggleButtons3 = document.querySelectorAll('.toggle-btn3');
            const toggleButtons4 = document.querySelectorAll('.toggle-btn4');


            // The number of schools by different time periods
            const totalSchools1 = @json($numOfSchools1);
            const totalSchools7 = @json($numOfSchools7);
            const totalSchools30 = @json($numOfSchools30);
            const totalSchools90 = @json($numOfSchools90);

            const totalStudents1 = @json($numOfStudents1);
            const totalStudents7 = @json($numOfStudents7);
            const totalStudents30 = @json($numOfStudents30);
            const totalStudents90 = @json($numOfStudents90);

            const totalVendors1 = @json($numOfVendors1);
            const totalVendors7 = @json($numOfVendors7);
            const totalVendors30 = @json($numOfVendors30);
            const totalVendors90 = @json($numOfVendors90);

            const totalBrands1 = @json($numOfBrands1);
            const totalBrands7 = @json($numOfBrands7);
            const totalBrands30 = @json($numOfBrands30);
            const totalBrands90 = @json($numOfBrands90);


            // const totalSchools1 = 1;
            // const totalSchools7 = 2;
            // const totalSchools30 = 3;
            // const totalSchools90 = 4;

            // const totalStudents1 = 5;
            // const totalStudents7 = 6;
            // const totalStudents30 = 7;
            // const totalStudents90 = 8;

            // const totalVendors1 = 9;
            // const totalVendors7 = 10;
            // const totalVendors30 = 11;
            // const totalVendors90 = 12;


            // const totalBrands1 = 13;
            // const totalBrands7 = 14;
            // const totalBrands30 = 15;
            // const totalBrands90 = 16;

            // Choses that button is gonna be selected when page is reloaded
            const defaultButton1 = document.querySelector('.toggle-btn1[data-range="24"]');
            const defaultButton2 = document.querySelector('.toggle-btn2[data-range="24"]');
            const defaultButton3 = document.querySelector('.toggle-btn3[data-range="24"]');
            const defaultButton4 = document.querySelector('.toggle-btn4[data-range="24"]');


            defaultButton1.classList.add('bg-blue-500', 'text-white');
            defaultButton2.classList.add('bg-blue-500', 'text-white');
            defaultButton3.classList.add('bg-blue-500', 'text-white');
            defaultButton4.classList.add('bg-blue-500', 'text-white');


            // Value that is displayed when you reload the page
            document.getElementById('selectedRange1').innerText = `${totalSchools1}`;
            document.getElementById('selectedRange2').innerText = `${totalStudents1}`;
            document.getElementById('selectedRange3').innerText = `${totalVendors1}`;
            document.getElementById('selectedRange4').innerText = `${totalBrands1}`;


            // What happens when you click a button for SCHOOLS
            toggleButtons1.forEach(button => {
                button.addEventListener('click', function () {
                    // Makes all buttons unselected
                    toggleButtons1.forEach(btn => {
                        btn.classList.remove('bg-blue-500', 'text-white');
                    });

                    // Add styles to the button you clicked
                    this.classList.add('bg-blue-500', 'text-white');

                    // Get the right time-range for the selected button
                    const selectedRange = this.getAttribute('data-range');

                    // Show the right values for when each button is selected
                    if (selectedRange == 24) {
                        document.getElementById('selectedRange1').innerText = `${totalSchools1}`;
                    } else if (selectedRange == 7) {
                        document.getElementById('selectedRange1').innerText = `${totalSchools7}`;
                    } else if (selectedRange == 30) {
                        document.getElementById('selectedRange1').innerText = `${totalSchools30}`;
                    } else if (selectedRange == 90) {
                        document.getElementById('selectedRange1').innerText = `${totalSchools90}`;
                    }
                });
            });

            toggleButtons2.forEach(button => {
                button.addEventListener('click', function () {
                    // Makes all buttons unselected
                    toggleButtons2.forEach(btn => {
                        btn.classList.remove('bg-blue-500', 'text-white');
                    });

                    // Add styles to the button you clicked
                    this.classList.add('bg-blue-500', 'text-white');

                    // Get the right time-range for the selected button
                    const selectedRange = this.getAttribute('data-range');

                    // Show the right values for when each button is selected
                    if (selectedRange == 24) {
                        document.getElementById('selectedRange2').innerText = `${totalStudents1}`;
                    } else if (selectedRange == 7) {
                        document.getElementById('selectedRange2').innerText = `${totalStudents7}`;
                    } else if (selectedRange == 30) {
                        document.getElementById('selectedRange2').innerText = `${totalStudents30}`;
                    } else if (selectedRange == 90) {
                        document.getElementById('selectedRange2').innerText = `${totalStudents90}`;
                    }
                });
            });

            toggleButtons3.forEach(button => {
                button.addEventListener('click', function () {
                    // Makes all buttons unselected
                    toggleButtons3.forEach(btn => {
                        btn.classList.remove('bg-blue-500', 'text-white');
                    });

                    // Add styles to the button you clicked
                    this.classList.add('bg-blue-500', 'text-white');

                    // Get the right time-range for the selected button
                    const selectedRange = this.getAttribute('data-range');

                    // Show the right values for when each button is selected
                    if (selectedRange == 24) {
                        document.getElementById('selectedRange3').innerText = `${totalVendors1}`;
                    } else if (selectedRange == 7) {
                        document.getElementById('selectedRange3').innerText = `${totalVendors7}`;
                    } else if (selectedRange == 30) {
                        document.getElementById('selectedRange3').innerText = `${totalVendors30}`;
                    } else if (selectedRange == 90) {
                        document.getElementById('selectedRange3').innerText = `${totalVendors90}`;
                    }
                });
            });

            toggleButtons4.forEach(button => {
                button.addEventListener('click', function () {
                    // Makes all buttons unselected
                    toggleButtons4.forEach(btn => {
                        btn.classList.remove('bg-blue-500', 'text-white');
                    });

                    // Add styles to the button you clicked
                    this.classList.add('bg-blue-500', 'text-white');

                    // Get the right time-range for the selected button
                    const selectedRange = this.getAttribute('data-range');

                    // Show the right values for when each button is selected
                    if (selectedRange == 24) {
                        document.getElementById('selectedRange4').innerText = `${totalBrands1}`;
                    } else if (selectedRange == 7) {
                        document.getElementById('selectedRange4').innerText = `${totalBrands7}`;
                    } else if (selectedRange == 30) {
                        document.getElementById('selectedRange4').innerText = `${totalBrands30}`;
                    } else if (selectedRange == 90) {
                        document.getElementById('selectedRange4').innerText = `${totalBrands90}`;
                    }
                });
            });
        }
    </script>
@endpush

</body>
</html>
