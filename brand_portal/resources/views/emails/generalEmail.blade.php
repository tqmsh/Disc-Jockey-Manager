<body>
    <p><strong>From: </strong> {{$sender->firstname}}  {{$sender->lastname}} | {{$sender->email}}</p>
    <p><strong>Category: </strong>Brand</p>
    <p><strong>Message: </strong></p>
    <x-markdown>
        {{$content}}
    </x-markdown> 
    <br>
    <p><strong>Sent from the Prom Planner Platform</strong></p>
</body>