
// release date init
module.exports = {
    getNextYear: function() {
        var date = new Date();
        var nextYear = date.getFullYear() + 1;
        var month = ('0' + (date.getMonth() + 1)).slice(-2);
        var day = date.getDate();
        var nextyear_today = nextYear + '-' + month + '-' + day;
        return nextyear_today;
       },

      /**
       * Get url params
       */
    getQueryVariable:function(variable) {
        var query = window.location.search.substring(1);
        var vars = query.split("&");
        for (var i = 0; i < vars.length; i++) {
          var pair = vars[i].split("=");
          if (pair[0] == variable) { return pair[1]; }
        }
        return (false);
      }
}
