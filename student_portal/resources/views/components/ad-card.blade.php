@props(['ad'])
<a href="{{  $ad['forward_url']  }}" target="_blank" class="card__url">
<div class="card" id = {{  $ad['id']  }}>
    <div class="card__body">
            <img src="{{  $ad['image_url']  }}" alt="AnImage" width="{{  env("AD_SIZE")  }}" height="{{  env("AD_SIZE")  }}" data-triggered="false">
        <br>
        <strong>{{  $ad['title']  }}</strong>
    </div>
    <span>
        {{  $ad['title']  }}
        <br>
        From: {{  $ad['company']  }}
        <br>
        Category: {{  $ad['category']  }}
    </span>
</div>
</a>

<script>
    document.getElementById({{  $ad['id']  }}).addEventListener("click", function () {
        var url = 'https://api.promplanner.app/api/campaign_click/' + encodeURIComponent({{  $ad['id']  }});
        axios.put(url)
    })
</script>
