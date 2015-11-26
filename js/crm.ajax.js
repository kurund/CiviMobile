// https://civicrm.org/licensing
/**
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/AJAX+Interface
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/Ajax+Pages+and+Forms
 */
(function($, CRM, undefined) {
  /**
   * @param string path
   * @param string|object query
   * @param string mode - optionally specify "front" or "back"
   */
  var tplURL;
  CRM.url = function (path, query, mode) {
    if (typeof path === 'object') {
      tplURL = path;
      return path;
    }
    if (!tplURL) {
      CRM.console('error', 'Error: CRM.url called before initialization');
    }
    if (!mode) {
      mode = CRM.config && CRM.config.isFrontend ? 'front' : 'back';
    }
    query = query || '';
    var frag = path.split('?');
    var url = tplURL[mode].replace("*path*", frag[0]);

    if (!query) {
      url = url.replace(/[?&]\*query\*/, '');
    }
    else {
      url = url.replace("*query*", typeof query === 'string' ? query : $.param(query));
    }
    if (frag[1]) {
      url += (url.indexOf('?') < 0 ? '?' : '&') + frag[1];
    }
    return url;
  };

  // @deprecated
  $.extend ({'crmURL':
    function (p, params) {
      CRM.console('warn', 'Calling crmURL from jQuery is deprecated. Please use CRM.url() instead.');
      return CRM.url(p, params);
    }
  });

  $.fn.crmURL = function () {
    return this.each(function() {
      if (this.href) {
        this.href = CRM.url(this.href);
      }
    });
  };

  /**
   * AJAX api
   */
  CRM.api3 = function(entity, action, params, status) {
    if (typeof(entity) === 'string') {
      params = {
        entity: entity,
        action: action.toLowerCase(),
        json: JSON.stringify(params || {})
      };
    } else {
      params = {
        entity: 'api3',
        action: 'call',
        json: JSON.stringify(entity)
      };
      status = action;
    }
    var ajax = $.ajax({
      url: CRM.url('civicrm/ajax/rest'),
      dataType: 'json',
      data: params,
      type: params.action.indexOf('get') < 0 ? 'POST' : 'GET'
    });
    if (status) {
      // Default status messages
      if (status === true) {
        status = {success: params.action === 'delete' ? ts('Removed') : ts('Saved')};
        if (params.action.indexOf('get') === 0) {
          status.start = ts('Loading...');
          status.success = null;
        }
      }
      var messages = status === true ? {} : status;
      CRM.status(status, ajax);
    }
    return ajax;
  };

  /**
   * @deprecated
   * AJAX api
   */
  CRM.api = function(entity, action, params, options) {
    // Default settings
    var settings = {
      context: null,
      success: function(result, settings) {
        return true;
      },
      error: function(result, settings) {
        $().crmError(result.error_message, ts('Error'));
        return false;
      },
      callBack: function(result, settings) {
        if (result.is_error == 1) {
          return settings.error.call(this, result, settings);
        }
        return settings.success.call(this, result, settings);
      },
      ajaxURL: 'civicrm/ajax/rest'
    };
    action = action.toLowerCase();
    // Default success handler
    switch (action) {
      case "update":
      case "create":
      case "setvalue":
      case "replace":
        settings.success = function() {
          CRM.status(ts('Saved'));
          return true;
        };
        break;
      case "delete":
        settings.success = function() {
          CRM.status(ts('Removed'));
          return true;
        };
    }
    params = {
      entity: entity,
      action: action,
      json: JSON.stringify(params)
    };
    // Pass copy of settings into closure to preserve its value during multiple requests
    (function(stg) {
      $.ajax({
        url: stg.ajaxURL.indexOf('http') === 0 ? stg.ajaxURL : CRM.url(stg.ajaxURL),
        dataType: 'json',
        data: params,
        type: action.indexOf('get') < 0 ? 'POST' : 'GET',
        success: function(result) {
          stg.callBack.call(stg.context, result, stg);
        }
      });
    })($.extend({}, settings, options));
  };
}(jQuery, CRM));

