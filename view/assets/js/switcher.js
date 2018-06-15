StagemPool = {
  body: $('body'),

  attachEvents: function () {
    this.attachChangePool();
  },

  // Show Print dialog
  attachChangePool: function () {
    // Remove handler from existing elements
    this.body.off('change', '.pool-switcher select', this.changePool);

    // Re-add event handler for all matching elements
    this.body.on('change', '.pool-switcher select', this.changePool);
  },

  changePool: function(event) {
    var self = arguments[0].target;
    var elm = $(self);
    var url = elm.find(':selected').data('url');
    document.location.href = url;
    //elm.trigger('change.change', data);
  }
};

jQuery(document).ready(function ($) {
  StagemPool.attachEvents();
});