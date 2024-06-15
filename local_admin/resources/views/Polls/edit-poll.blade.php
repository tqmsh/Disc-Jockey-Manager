@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Polls</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/datepicker.min.js"></script>
</head>
<body>
<section class="max-w px-6 pb-2 pt-1 mx-auto bg-white rounded-md shadow-md dark:bg-gray-800">
    <form 
        action="{{ route('poll.update', $poll) }}" method="PUT">
        <div id="form-wrapper" class="grid grid-cols-2 gap-3 mt-4 sm:grid-cols-2">
            <div class="col-span-2">
                <label class="text-gray-700 dark:text-gray-200" for="title">Title</label>
                <input id="title" name="title" value="{{ $poll->title }}" type="text" class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring">
            </div>

            <div class="col-span-2">
                <label class="text-gray-700 col-span-2 dark:text-gray-200" for="emailAddress">Short Description</label>
                <input id="description" name="description" value="{{ $poll->description }}" type="text" class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring">
            </div>

            <!-- DATE PICKING-->
            <label class="text-gray-700 col-span-2 dark:text-gray-200">Customize Poll Duration</label>
            <div date-rangepicker class="flex flex-row col-span-2">
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                        </svg>
                    </div>
                    <input name="start_date" type="text" value="{{ $poll->start_date }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 ml-10 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date start">
                </div>
                <span class="mx-4 text-gray-500">to</span>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                            </svg>
                        </div>
                        <input name="end_date" type="text" value="{{ $poll->end_date }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 ml-10 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date end">
                    </div>
            </div>
            
            <!-- Voting Options-->
            <label class="text-gray-700 col-span-2 dark:text-gray-200">Voting Options</label>

    <!-- Example input -->
    <!-- <div class="flex flex-row items-center">
        <label class="text-gray-700 dark:text-gray-200 font-bold p-2 text-lg" for="option_1">1</label>
        <input id="option_1" name="option_1" value="{{ $options[0]->title }}" type="text" class="block w-full px-4 py-2 mt-0 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring" placeholder="Type your answer here">
    </div>


            <div class="flex flex-row items-center ">
                <label class="text-gray-700 dark:text-gray-200 font-bold p-2 text-lg" for="option_2">2</label>
                <input id="option_2" name="option_2" value="{{ $options[1]->title }}" type="text" class="block w-full px-4 py-2 mt-0 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring" placeholder="Type your answer here">
            </div> -->



            </div> <!-- end of grid -->

        <div class="flex justify-start mt-2">
            <button id="createButton" type="button" class="flex flex-row items-center gap-x-2 text-gray-900 bg-white border-1 border-black  hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg px-3 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
            Add Option
            </button>

        </div>

        <div class="flex justify-end mt-6">
        <!-- <a href="/" class="px-8 py-2.5 leading-5 text-white transition-colors duration-300 transform bg-gray-700 rounded-md hover:bg-gray-600 focus:outline-none focus:bg-gray-600">Create
            <button class="px-8 py-2.5 leading-5 text-white transition-colors duration-300 transform bg-gray-700 rounded-md hover:bg-gray-600 focus:outline-none focus:bg-gray-600">Create</button> 
            <a href="/" class="text-black ml-4"> Back </a> 
        </a> -->

        <button type="submit" class="px-8 py-2.5 leading-5 text-white transition-colors duration-300 transform bg-gray-700 rounded-md hover:bg-gray-600 focus:outline-none focus:bg-gray-600">Save</button> 
</div>



    </form>
    </section>

    </body>
</html>


<script language="javascript" type="text/javascript">
    document.addEventListener("DOMContentLoaded", function () {
        const createButton = document.getElementById("createButton");
        const inputContainer = document.getElementById("form-wrapper");

        const options = <?php echo json_encode($options); ?>;

        // Create input fields for existing options
        options.forEach((option, index) => {
            const newInput = document.createElement("div");
            newInput.classList.add("flex", "flex-row", "items-center");

            const label = document.createElement("label");
            label.setAttribute("for", "option_" + (index + 1));
            label.classList.add("text-gray-700", "dark:text-gray-200", "font-bold", "p-2", "text-lg");
            label.textContent = (index + 1);

            const input = document.createElement("input");
            input.setAttribute("id", "option_" + (index + 1));
            input.setAttribute("name", "option_" + (index + 1));
            input.setAttribute("type", "text");
            input.classList.add("block", "w-full", "px-4", "py-2", "mt-0", "text-gray-700", "bg-white", "border", "border-gray-200", "rounded-md", "dark:bg-gray-800", "dark:text-gray-300", "dark:border-gray-600", "focus:border-blue-400", "focus:ring-blue-300", "focus:ring-opacity-40", "dark:focus:border-blue-300", "focus:outline-none", "focus:ring");
            input.setAttribute("placeholder", "Type your answer here");
            input.setAttribute("value", option.title);
            // input.value = $options[index]->title;

            newInput.appendChild(label);
            newInput.appendChild(input);

            inputContainer.appendChild(newInput);
        });

        let inputCount = options.length; // Update inputCount with the number of existing options

    //     setTimeout(function() {
    //     // Your action to be performed after delay        
    //     location.reload();
    // }, 500); // 500 milliseconds delay


        createButton.addEventListener("click", function () {


            if (inputCount == 9) {
                createButton.remove();
            }
            if (inputCount < 10) {
                inputCount++;
                const newInput = document.createElement("div");
                newInput.classList.add("flex", "flex-row", "items-center");

                const label = document.createElement("label");
                label.setAttribute("for", "option_" + inputCount);
                label.classList.add("text-gray-700", "dark:text-gray-200", "font-bold", "p-2", "text-lg");
                label.textContent = inputCount;

                const input = document.createElement("input");
                input.setAttribute("id", "option_" + inputCount);
                input.setAttribute("name", "option_" + inputCount);
                input.setAttribute("type", "text");
                input.classList.add("block", "w-full", "px-4", "py-2", "mt-0", "text-gray-700", "bg-white", "border", "border-gray-200", "rounded-md", "dark:bg-gray-800", "dark:text-gray-300", "dark:border-gray-600", "focus:border-blue-400", "focus:ring-blue-300", "focus:ring-opacity-40", "dark:focus:border-blue-300", "focus:outline-none", "focus:ring");
                input.setAttribute("placeholder", "Type your answer here");

                newInput.appendChild(label);
                newInput.appendChild(input);

                inputContainer.appendChild(newInput);
            }
        });
    });
</script>
@endsection