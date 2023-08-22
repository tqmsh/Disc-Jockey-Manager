<body>
    <p><strong>From: </strong> {{$sender_email}}</p>
    <p><strong>Message: </strong></p>
    {{app(Spatie\LaravelMarkdown\MarkdownRenderer::class)->toHtml($content)}}
    <br>
    <p><strong>Sent from the Prom Planner Platform</strong></p>
</body>