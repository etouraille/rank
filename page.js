var page = require('webpage').create();
page.open('https://www.lemonde.fr/archives-du-monde/03-01-97', function() {
  page.includeJs("http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js", function() {
    function _x(STR_XPATH) {
    var xresult = document.evaluate(STR_XPATH, document, null, XPathResult.ANY_TYPE, null);
    var xnodes = [];
    var xres;
      while (xres = xresult.iterateNext()) {
        xnodes.push(xres);
      } 

      return xnodes;
    }
    page.evaluate(function() {
      $(_x('html/.//section[@id="river"]/section/a')).click();
    });
    phantom.exit()
  });
});
