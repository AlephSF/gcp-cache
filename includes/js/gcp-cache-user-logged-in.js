! JSON.parse(window.localStorage.getItem('gcpCacheBuster')) && window.localStorage.setItem('gcpCacheBuster', true);

if(JSON.parse(window.localStorage.getItem('gcpCacheBuster'))) {
  console.log('logged in');
}
jQuery(function() {
  var siteURL = "https://" + top.location.host.toString();
  jQuery('body').not('.wp-admin').find("a[href^='"+siteURL+"'], a[href^='/'], a[href^='./'], a[href^='../'], a[href^='#']").attr("href", function(i, href) {

    if (jQuery(this).parents('#wpadminbar').length) {
      return href;
    }
    
    try {
      const url = new URL(href)
      const params = new URLSearchParams(url.search);
      params.set('bustcache', true);
      return `${url.pathname}?${params}`;
    } catch (e) {
      return href;
    }
  });
});
