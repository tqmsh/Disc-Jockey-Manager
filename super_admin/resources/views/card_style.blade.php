<style>
    .card {
        --bg: #e2e2e3;
        --hover-bg: #8b21d7;
        --hover-text: #ffffff;
        text-align: center;
        background: var(--bg);
        padding-block: 1.3em;
        border-radius: 3px;
        position: relative;
        overflow: hidden;
        transition: .3s cubic-bezier(.6,.4,0,1),transform .15s ease;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 1em;
        padding: 1.2em 1.2em 0.3em;
        max-width: calc({{  env("AD_SIZE")  }}px + 2.4em);
    }
    .card__url{
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        max-width: calc({{  env("AD_SIZE")  }}px + 2.4em);
    }

    .card__body {
        color: #3d3d3d;
        line-height: 1.5em;
        font-size: 1.5em;
    }

    .card > :not(span) {
        transition: .3s cubic-bezier(.6,.4,0,1);
    }

    .card > strong {
        vertical-align: center;
        display: block;
        font-size: 1.4rem;
        letter-spacing: -.035em;
    }

    .card span {
        font-size: 20em;
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        color: var(--hover-text);
        border-radius: 3px;
        font-weight: bold;
        top: 10000%;
        transition: all .3s cubic-bezier(.6,.4,0,1);
    }

    .card:hover span {
        top: 0;
        font-size: 1.4em;
    }

    .card:hover {
        background: var(--hover-bg);
    }

    .card:hover>div,.card:hover>strong {
        opacity: 0;
    }
    @media screen and (max-width: 991px){
        img{
            width: min(calc(100% - 20px), {{  env("AD_SIZE")  }}px);
            height: min(calc(100% - 20px), {{  env("AD_SIZE")  }}px);
        }
    }
    @media screen and (max-width: 767px){
        img{
            width: min(calc(100% - 20px), {{  env("AD_SIZE")  }}px);
            height: min(calc(100% - 20px), {{  env("AD_SIZE")  }}px);
        }
    }
    @media screen and (max-width: 479px){
        img{
            width: min(calc(100% - 20px), {{  env("AD_SIZE")  }}px);
            height: min(calc(100% - 20px), {{  env("AD_SIZE")  }}px);
        }
    }
</style>

<script>
    function sendInternalRequestWithIdParam(image) {
        id = image.id
        let triggered_ = image.dataset.triggered
        if (triggered_ !== "true") {
            // prepare the request URL with ID parameter
            var url = 'https://api.promplanner.app/api/campaign_view/' + encodeURIComponent(id);

            axios.put(url)
            image.dataset.triggered = "true";
        }
    }

    function checkIfImageIsVisible(image, callback) {
        if (image != null) {
            var rect = image.getBoundingClientRect();
            var viewHeight = Math.max(document.documentElement.clientHeight, window.innerHeight);
            if (rect.bottom >= 0 && rect.top < viewHeight) {
                callback(image);
            }
        }
    }

    window.addEventListener("load", function () {
        for (let card_ of document.getElementsByClassName("card"))
        {
            image = card_.getElementsByTagName("img")[0]
            checkIfImageIsVisible(image, sendInternalRequestWithIdParam);
        }
    });

    window.addEventListener('scroll', function () {
        for (let card_ of document.getElementsByClassName("card")) {
            image = card_.getElementsByTagName("img")[0]
            checkIfImageIsVisible(image, sendInternalRequestWithIdParam);
        }
    });
</script>
