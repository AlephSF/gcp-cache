jQuery(function() {
  var gcpCacheLoggedInCookie = ajaxLoggedInObject.gcpCacheLoggedInCookie
  var wpLoggedInCookieExists = document.cookie.split(';').some(function(c) {
    return c.trim().indexOf(gcpCacheLoggedInCookie) === 0;
  })

  if (wpLoggedInCookieExists) {
    var queryParamKey = 'bustcache';
    var queryParam = queryParamKey + '=true';

    var bustcachequeryParamIndex = window.location.search.indexOf(queryParam);
    var currentUrl = window.location.href;    
    if (bustcachequeryParamIndex === -1) {
      if (currentUrl.indexOf('?') !== -1) {
         currentUrl = currentUrl + '&' + queryParam;
      } else {
         currentUrl = currentUrl + '?' + queryParam;
      }
      window.location.href = currentUrl;
    } else {
      //Remove cache busting query parameter from URL bar
      window.history.replaceState({}, '', currentUrl.replace('?' + queryParam, '').replace('&' + queryParam, ''));
      var siteURL = window.location.host.toString();
      jQuery('body').not('.wp-admin').find("a[href^='http://"+siteURL+"'], a[href^='https://"+siteURL+"'], a[href^='/']").attr("href", function(i, href) {

        if (jQuery(this).parents('#wpadminbar').length) {
          return href;
        }
        
        try {
          const url = new URL(href)
          const params = new URLSearchParams(url.search);
          params.set(queryParamKey, true);
          return `${url.pathname}?${params}`;
        } catch (e) {
          return href;
        }
      });
    }
  }
});
