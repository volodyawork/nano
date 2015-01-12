window.redactorUploadFile = function (obj, json) {
    obj.attr('src', obj.attr('src').substr(obj.attr('src').indexOf('../web/') + 6));
};