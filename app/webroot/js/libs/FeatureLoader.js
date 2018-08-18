/*    HTTP Host:  static.ak.connect.facebook.com                               */
/*    Generated:  September 15th 2009 5:45:05 AM PDT                           */
/*      Machine:  10.17.55.147                                                 */

/**
 * FeatureLoader.js.php is designed to be the minimal set of code necessary to
 * use the Facebook Javascript API.
 *
 * The majority of the API is split into "features" which can be loaded
 * separately or all at once. Most features are packaged together into
 * connect.js.pkg.php, so it doesn't really matter which ones you pull in.
 *
 * @provides connect-FeatureLoader
 *
 */

// Create FB namespace if necessary
if (!window.FB) {
  FB = {};
}

// Only load if this class is not already loaded
if (!FB.Bootstrap) {
  FB.Bootstrap = {
    /*
     * Use this to request loading of features in Facebook Client JavaScript library
     * @param features  array of features (see wiki for options)
     * @param callback  callback function to be executed when all required features
     *                  are finished loading
     */
    requireFeatures : function(features, callback) {
      // Don't do anything if this page is a cross domain channel page
      if (FB.Bootstrap.isXdChannel) {
        return;
      }

      FB.Bootstrap.enqueueFeatureRequest({"features": features,
            "callback": callback,
            "loadedCount": 0});
      if (FB.Bootstrap.FeatureMap) {
        if (FB.FeatureLoader) {
          FB.FeatureLoader.singleton.checkRequestQueue();
        } else {
          FB.Bootstrap.addScript(FB.Bootstrap.FeatureMap["Base"].src);
        }
      }
    },

    /*
     * Convenient wrapper for calling Facebook API calls. Because the Facebook
     * API is dynamically loaded, this guarantees that your function isn't called
     * until both the libraries are loaded and initialized.
     *
     * Use in conjunction with FB.init.
     * Example usage:
     *  FB.ensureInit (  function () {
     *  // ... any code in the Facebook library
     *  });
     *
     * @param callback   function to call when Facebook is dynamically loaded.
     * @throws exception if FB.init is not called within the document.
     */
    ensureInit : function(callback) {
      if (!callback) {
        throw("FB.ensureInit called without a valid callback");
      }

      // short-circuit if initialization has already been called
      if (FB.Facebook &&
          FB.Facebook.get_initialized &&
          FB.Facebook.get_initialized().get_isReady() &&
          FB.Facebook.get_initialized().result) {
        return callback();
      }

      // if it's not already initialized, then queue it up
      // by the time this callback is executed, FB.Facebook.init
      // must have been called or else
      FB.Bootstrap.requireFeatures(FB.Bootstrap.features, function() {
        FB.Facebook.get_initialized().waitForValue(true, callback);
        });
    },

    /*
     * This safely initializes the Facebook API for use on a Connect or iframe site.
     *
     * It is a wrapper around FB.Facebook.init, provided here so that it is available
     * before the rest of the library is dynamically loaded. All subsequent calls
     * must be wrapped in FB.ensureInit() in order to guarantee that the init function is
     * called first.
     *
     * Example Usage:
     *  FB.init("API_KEY", "xd_receiver.php");
     *
     *  @param api_key       your API key provided by the developer app
     *  @param xd_receiver   The cross-domain receiver file on your domain.
     *                       Suggest using an absolute URL like "/xd_receiver.htm"
     *  @param appSettings   Optional application settings.
     */
    init : function(api_key, xd_receiver, appSettings) {
      // bind to the onload handler
      FB.Bootstrap.requireFeatures(FB.Bootstrap.features, function() {
          if (FB.Facebook) {
            // init has changed definition by now
            FB.Facebook.init(api_key, xd_receiver, appSettings);
          }
        });
    },

    /*
     * Dynamically add a script tag to the document.
     */
    addScript : function(src) {
      var scriptElement;

      // Check if we have the script loaded already
      var scriptElements = document.getElementsByTagName('script');
      if (scriptElements ) {
        var c = scriptElements.length;
        for (var i = 0; i < c; i++) {
          scriptElement = scriptElements[i];
          if (scriptElement.src == src) {
            // Found a match
            return;
          }
        }
      }


      scriptElement = document.createElement("script");
      scriptElement.type = "text/javascript";
      scriptElement.src = src;
      var parent = document.getElementsByTagName('HEAD')[0] || document.body;
      parent.appendChild(scriptElement);
    },

    /*
     * Initialize global page, one-time setup for the cross domain channel.
     *
     * Some sites may not have a dedicated cross domain channel page, but
     * use an existing page url as the channel page by using the special
     * fbc_receiver=1 query parameter. This is not very performant but we
     * support in cases where a dedicated channel is difficult to create.
     *
     */
    initializeXdChannel : function () {
      FB.Bootstrap.isXdChannel =
        window.location.search.indexOf(FB.Bootstrap.fbc_channel_token) >= 0;

      if (!FB.Bootstrap.isXdChannel) {
        FB.Bootstrap.createHiddenDiv();
        FB.Bootstrap.detectDOMContentReady();
      }
    },

    /*
     * Use detectDOMContentReady to determine whether window is loaded.
     * Because there is no way to determine a window is loaded after it is
     * already loaded, we must initialize the state to false in a code that
     * will be executed before the window is loaded, then listen to the window
     * load event.
     *
     * Since FeatureLoader.js.php is the only script we have that is not
     * dynamically loaded, we must place this code in this file.
     */
    detectDOMContentReady : function() {
      if (window.navigator.userAgent.toLowerCase().indexOf("msie") >= 0) {
        window.attachEvent("onload", function() {
            FB.Bootstrap.IsDomContentReady = true;
          });
      } else {
        window.addEventListener("DOMContentLoaded", function() {
            FB.Bootstrap.IsDomContentReady = true;
          }, false);
      }
    },

    /*
     * Create a hidden DOM container element. This is used to store hidden
     * iframes. If developers do not want the document.write to be called,
     * they can create their own hidden div named "FB_HiddenContainer".
     */
    createHiddenDiv : function() {
      if (document.getElementById('FB_HiddenContainer') == null) {
        document.write('<div id="FB_HiddenContainer" '
                       + 'style="position:absolute; top:-10000px; '
                       + 'width:0px; height:0px;" >'
                       + '</div>');
      }
    },

    /*
     * Loads the map of feature => file that enables dynamic loading of JS files.
     * Note that for now, these are pretty much all pointing to the same file,
     * but we hope to implement some optimizations in the future to make this
     * more customizable.
     *
     * @param  featureMap          map of feature => file
     */
    loadServerMaps : function(featureMap) {
      if(!this.FeatureMap.length) {
        this.FeatureMap = featureMap;
        if (FB.FeatureLoader) {
          FB.FeatureLoader.singleton.checkRequestQueue();
        }
      }
    },

    /*
     * Set the locale for the loaded resources.
     */
    setLocale : function(locale, isRTL) {
      window.FB.locale = locale;
      window.FB.localeIsRTL = isRTL;
    },

    /*
     * Submit a given feature request for loading.
     */
    enqueueFeatureRequest : function(request) {
      this.FeatureRequestQueue[this.FeatureRequestQueue.length] = request;
    },

    /*
     * For IE, we will try to detect if document.namespaces contains 'fb' already
     * and add it if it does not exist.
     */
    detectDocumentNamespaces : function() {
      if (document.namespaces && !document.namespaces.item['fb']) {
        document.namespaces.add('fb');
      }
    },

    /*
     * If a dedicated cross domain channel url cannot be created.
     * Use this function create an url based on current page by
     * adding a special query string the url of the current page.
     * This should be avoided unless there is other choice because
     * it is not efficient.
     */
    createDefaultXdChannelUrl : function() {
      var xd_receiver = location.protocol + '//' + location.hostname +
      location.pathname + location.search;
      if(location.search || location.search.length > 0) {
        xd_receiver += '&';
      } else {
        xd_receiver += '?';
      }
      xd_receiver += 'fbc_channel=1';
      return xd_receiver;
    },

    /*
     * Global state variables
     */
    features                 : ["XFBML", "CanvasUtil"],
    FeatureMap               : [],
    IsDomContentReady        : false,
    FeatureRequestQueue      : [],
    CustomFeatureMap         : [],
    fbc_channel_token        : 'fbc_channel=1'
  };

  /*
   * Define shorthand functions for ease of use.
   */
  window.FB_RequireFeatures        = FB.Bootstrap.requireFeatures;
  window.FB.init                   = FB.Bootstrap.init;
  window.FB.ensureInit             = FB.Bootstrap.ensureInit;
}

FB.Bootstrap.initializeXdChannel();
FB.Bootstrap.detectDocumentNamespaces();


if (!FB.HiddenContainer) {
  FB.HiddenContainer = {
    get: function() {
      return document.getElementById('FB_HiddenContainer');
    }
  };
}
if (!window.FB) {FB = {};}
                    if(!FB.dynData){ FB.dynData = {"site_vars":{"canvas_client_compute_content_size_method":1,"use_postMessage":1,"enable_custom_href":0},"resources":{"base_url_format":"http:\/\/{0}.connect.facebook.com\/","api_channel":187011,"api_server":163033,"www_channel":187011,"xd_comm_swf_url":"http:\/\/static.ak.connect.facebook.com\/swf\/XdComm.swf","login_img_dark_small_short":"http:\/\/b.static.ak.fbcdn.net\/rsrc.php\/zF1W2\/hash\/a969rwcd.gif","login_img_dark_medium_short":"http:\/\/b.static.ak.fbcdn.net\/rsrc.php\/zEF9L\/hash\/156b4b3s.gif","login_img_dark_medium_long":"http:\/\/b.static.ak.fbcdn.net\/rsrc.php\/zBIU2\/hash\/85b5jlja.gif","login_img_dark_large_short":"http:\/\/b.static.ak.fbcdn.net\/rsrc.php\/z1UX3\/hash\/a22m3ibb.gif","login_img_dark_large_long":"http:\/\/static.ak.fbcdn.net\/rsrc.php\/z7SXD\/hash\/8mzymam2.gif","login_img_light_small_short":"http:\/\/b.static.ak.fbcdn.net\/rsrc.php\/zDGBW\/hash\/8t35mjql.gif","login_img_light_medium_short":"http:\/\/static.ak.fbcdn.net\/rsrc.php\/z38X1\/hash\/6ad3z8m6.gif","login_img_light_medium_long":"http:\/\/b.static.ak.fbcdn.net\/rsrc.php\/zB6N8\/hash\/4li2k73z.gif","login_img_light_large_short":"http:\/\/static.ak.fbcdn.net\/rsrc.php\/zA114\/hash\/7e3mp7ee.gif","login_img_light_large_long":"http:\/\/b.static.ak.fbcdn.net\/rsrc.php\/z4Z4Q\/hash\/8rc0izvz.gif","login_img_white_small_short":"http:\/\/static.ak.fbcdn.net\/rsrc.php\/z900E\/hash\/di0gkqrt.gif","login_img_white_medium_short":"http:\/\/static.ak.fbcdn.net\/rsrc.php\/z10GM\/hash\/cdozw38w.gif","login_img_white_medium_long":"http:\/\/static.ak.fbcdn.net\/rsrc.php\/zBT3E\/hash\/338d3m67.gif","login_img_white_large_short":"http:\/\/static.ak.fbcdn.net\/rsrc.php\/zCOUP\/hash\/8yzn0wu3.gif","login_img_white_large_long":"http:\/\/static.ak.fbcdn.net\/rsrc.php\/zC6AR\/hash\/5pwowlag.gif","logout_img_small":"http:\/\/static.ak.fbcdn.net\/rsrc.php\/z2Y31\/hash\/cxrz4k7j.gif","logout_img_medium":"http:\/\/static.ak.fbcdn.net\/rsrc.php\/zAD8D\/hash\/4lsqsd7l.gif","logout_img_large":"http:\/\/b.static.ak.fbcdn.net\/rsrc.php\/zB36N\/hash\/4515xk7j.gif"}};}
                    FB.Bootstrap.loadServerMaps(
        /* featureMap        */ {"Base":{"src":"http:\/\/static.ak.fbcdn.net\/rsrc.php\/zEVUA\/hash\/5s5y4rmx.js","dependencies":null},"Common":{"src":"http:\/\/static.ak.fbcdn.net\/rsrc.php\/zEVUA\/hash\/5s5y4rmx.js","dependencies":["Base"]},"XdComm":{"src":"http:\/\/static.ak.fbcdn.net\/rsrc.php\/zEVUA\/hash\/5s5y4rmx.js","dependencies":["Common"]},"CacheData":{"src":"http:\/\/b.static.ak.fbcdn.net\/rsrc.php\/z71TX\/hash\/ds9pbuv9.js","dependencies":["Common","XdComm"]},"Api":{"src":"http:\/\/static.ak.fbcdn.net\/rsrc.php\/zEVUA\/hash\/5s5y4rmx.js","dependencies":["XdComm"]},"CanvasUtil":{"src":"http:\/\/static.ak.fbcdn.net\/rsrc.php\/zEVUA\/hash\/5s5y4rmx.js","dependencies":["Common","XdComm"]},"Connect":{"src":"http:\/\/static.ak.fbcdn.net\/rsrc.php\/zEVUA\/hash\/5s5y4rmx.js","dependencies":["CanvasUtil","Api"],"styleSheets":["http:\/\/static.ak.fbcdn.net\/rsrc.php\/z8PAT\/hash\/5shq2uh6.css"]},"XFBML":{"src":"http:\/\/static.ak.fbcdn.net\/rsrc.php\/zEVUA\/hash\/5s5y4rmx.js","dependencies":["CanvasUtil","Api","Connect"]},"Integration":{"src":"http:\/\/static.ak.fbcdn.net\/rsrc.php\/z2K8F\/hash\/196atmkc.js","dependencies":["Connect"]},"Comments":{"src":"http:\/\/b.static.ak.fbcdn.net\/rsrc.php\/z8TP9\/hash\/194vhs1d.js","dependencies":["XdComm","XFBML"]},"Payments":{"src":"http:\/\/static.ak.fbcdn.net\/rsrc.php\/zDGT0\/hash\/7hmkczwl.js","dependencies":["XdComm","Connect"]}});
        FB.Bootstrap.setLocale("en_US", false);/**
 *  NOTE - this file should be editted at
 *  /lib/connect/Facebook/XdComm/XdCommReceiver.js
 *  which will rewrite any library file connect is autogened
 *
 *  @provides XdCommReceiver
 *  @requires
 */

// Create FBIntern namespace if necessary
if (!window.FBIntern) {
  FBIntern = {};
}

// Only load if this class is not already loaded
if (!FBIntern.XdReceiver) {
  // XdReceiver class
  FBIntern.XdReceiver = {
    delay : 100,
    timerId : -1,
    dispatchMessage: function() {
      //We don't used window.location.hash because it has different behavior on IE and Firefox.
      //See https://bugzilla.mozilla.org/show_bug.cgi?id=378962
      var pathname = document.URL;
      var hashIndex = pathname.indexOf('#');
      var hash;
      if(hashIndex > 0) {
        hash = pathname.substring(hashIndex + 1);
      } else {
        //hashIndex not found;
        //Check if it's special case for login callback
        hashIndex = pathname.indexOf('fb_login&');
        if(hashIndex > 0) {
          hash = pathname.substring(hashIndex + 9);
        } else {
          return;
        }
      }

      var debugFlag='debug=1&';
      if(hash.indexOf(debugFlag) == 0) {
        hash = hash.substring(debugFlag.length);
      }

      var packet_string;
      var func = null;
      try {
        var hostWindow = window.parent;
        if (hash.indexOf('fname=') == 0) {
          var packetStart = hash.indexOf('&');
          var frame_name = hash.substr(6, packetStart-6);
          if(frame_name == "_opener") {
            hostWindow = hostWindow.opener;
          } else if (frame_name == "_oparen") {
            hostWindow = hostWindow.opener.parent;
          } else if (frame_name != "_parent") {
            hostWindow = hostWindow.frames[frame_name];
          }
          packet_string = hash.substr(packetStart+1);
        } else {
          hostWindow = hostWindow.parent;
          packet_string = hash;
        }

        func = hostWindow.FB.XdComm.Server.singleton.onReceiverLoaded;
      } catch (e) {
        if (e.number == -2146828218) {
          //Permission denied
          return;
        }
      }

      if(func) {
        hostWindow.FB.XdComm.Server.singleton.onReceiverLoaded(packet_string);
        if(FBIntern.XdReceiver.timerId != -1) {
          window.clearInterval(FBIntern.XdReceiver.timerId);
          FBIntern.XdReceiver.timerId = -1;
        }
      } else {
        if(FBIntern.XdReceiver.timerId == -1) {
          try {
            FBIntern.XdReceiver.timerId = window.setInterval(FBIntern.XdReceiver.dispatchMessage, FBIntern.XdReceiver.delay);
          } catch (e) {
          }
        }
      }
    }
  };

  if (!(window.FB && FB.Bootstrap && !FB.Bootstrap.isXdChannel)) {
    try {
      FBIntern.XdReceiver.dispatchMessage();
    }
    catch(e) {
    }
  }
 }


(function() {
  // get script tag and see if it has an apikey
  // if there is an api key then call FB.init
  var scripts = document.getElementsByTagName('script');
  var this_script_tag = scripts[scripts.length - 1]; //script tag of this file
  if (this_script_tag != undefined) {
    var apikey = this_script_tag.getAttribute('fb-api-key');
    var receiver = this_script_tag.getAttribute('fb-xd-receiver');
    if (apikey != null) {
      window.setTimeout(
        function() {
          FB.init(apikey, receiver);
        },
        0);
    }
  }
})();
function f_reload(){
    window.location.reload();
}

