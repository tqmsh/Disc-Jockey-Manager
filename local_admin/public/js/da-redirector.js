if(document.getElementById('aChBjqEXzGJk') !== null){
    var currentUrl = window.location.href;
    var baseUrl = currentUrl.split('/').slice(0, 3).join('/');

    window.location.replace(baseUrl + "/admin/dashboard");
}