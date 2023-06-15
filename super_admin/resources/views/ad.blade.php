<a href="{{ $forward_url }}" target="_blank" class="card__url">
<div class="card" id = {{  $id  }}>
    <div class="card__body">
            <img src="{{  $image_url  }}" alt="AnImage" width="{{  env("AD_SIZE")  }}" height="{{  env("AD_SIZE")  }}" data-triggered="false">
        <br>
        <strong>{{  $title  }}</strong>
    </div>
    <span>
        {{  $title  }}
        <br>
        From: {{  $company  }}
        <br>
        Category: {{  $category  }}
    </span>
</div>
</a>

<script>
    document.getElementById({{  $id  }}).addEventListener("click", function () {
        var url = 'https://api.promplanner.app/api/campaign_click/' + encodeURIComponent({{  $id  }});
        axios.put(url)
    })
</script>
