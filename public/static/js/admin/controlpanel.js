var ControlPanelPlatform = function () {
    var MODULE_UTILS_ROOT_PATH = "/utils";
    var MODULE_ADMIN_ROOT_PATH = "/admin";

    var tinyIpConvertedCachePool = {};

    this.tinyipConvert = function (ip) {
        if (tinyIpConvertedCachePool[ip] != null)
            return tinyIpConvertedCachePool[ip];
        else {
            var converted = "";
            $.ajax({
                data:"ip=" + ip,
                type:"post",
                url:MODULE_UTILS_ROOT_PATH + "/tinyip",
                async:false,
                success:function (transport) {
                    converted = transport;
                    tinyIpConvertedCachePool[ip] = converted;
                }
            });
            return converted;
        }
    };
};

var controlPanel = new ControlPanelPlatform();