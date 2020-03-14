(function(Icinga) {
    var Oncall = function(module) {
        this.module = module;
        this.initialize();
        this.module.icinga.logger.debug('OnCall module loaded');
    };

    Oncall.prototype = {
        initialize: function()
        {
            this.module.icinga.logger.debug('OnCall module initialized');
        }
    };

    Icinga.availableModules.oncall = Oncall;
}(Icinga));

