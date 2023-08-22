<body>
    <p><strong>From: </strong> {{$sender_email}}</p>
    <p><strong>Message: </strong></p>
    <x-markdown>
        {{$content}}
    </x-markdown> 
    <br>
    <p><strong>Sent from the Prom Planner Platform</strong></p>
</body>