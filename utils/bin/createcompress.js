var fs = require('fs');

Function.prototype.bind = function(obj) {
    var fn = this;
    return function () {
        return fn.apply(obj, arguments);
    };
}

var arguments = process.argv.splice(2);

var App = {};
App.jsPath = arguments[0] ? arguments[0] : "../../www/js/";
App.defaultConfigPath = App.jsPath + "lib/mjsb/bootstrap.js";
App.projectConfigPath = App.jsPath + "config.js";
App.compressConfigPath = App.jsPath + "lib/mjsb/compress.js";
App.selectedConfigItems = ['baseUrl', 'paths', 'out', 'name', 'shim'];
App.initialize = function() {
    MJSBFile.readConfig(this.defaultConfigPath, function(config) {
        var defaultConfig = MJSBFile.selectConfigItems(config, this.selectedConfigItems);
        MJSBFile.readConfig(this.projectConfigPath, function(config) {
            var projectConfig = MJSBFile.selectConfigItems(config, this.selectedConfigItems);
            var mergedConfig = MJSBFile.mergeConfigs(defaultConfig, projectConfig);
            MJSBFile.writeConfig(this.compressConfigPath, mergedConfig);
        }.bind(this));
    }.bind(this));
};

var MJSBFile = {};
MJSBFile.configRegexp = /var .*Config \= \{((.|[\r\n])*?)\}\;/;
MJSBFile.replaceConfigVarRegExp = /var .*Config/;
MJSBFile.readConfig = function(filename, callback) {
    fs.readFile(filename, function(error, data) {
        if (error) throw error;
        var matches = this.configRegexp.exec(data);
        if(matches.length > 0) {
            evalString = matches[0].replace(this.replaceConfigVarRegExp, 'var config');
            eval(evalString);
            callback(config);
        }
    }.bind(this));
};
MJSBFile.writeConfig = function(filename, config) {
    fs.writeFile(filename, '(' + JSON.stringify(config, null, 4) + ')', function(error) {
        if (error) throw error;
    });
};
MJSBFile.mergeConfigs = function(defaultConfig, config){
    for(var key in defaultConfig) {
        if(! config[key]) {
            config[key] = defaultConfig[key];
        }
        if(typeof(config[key]) === 'object' && ! (config[key] instanceof Array)) {
            config[key] = this.mergeConfigs(defaultConfig[key], config[key]);
        }
    }
    return config;
};
MJSBFile.selectConfigItems = function(config, selectedItems) {
    var selectedConfig = {};
    for(var index in selectedItems) {
        if(config[selectedItems[index]]) {
            selectedConfig[selectedItems[index]] = config[selectedItems[index]];
        }
    }
    return selectedConfig;
};

App.initialize();
