function dateStringToFormat(dateString, type = "date-time") {

  if (dateString == null || dateString == ''){
    return '';
  }

  var date = new Date(dateString);

  if (type === "date-time") {
    return formatDateTime(date);
  }

  if (type === "datetime-local") {
    return formatDateTimeLocal(date);
  }

  if (type === "time") {
    return formatTime(date);
  }
}

function secondsToTimeFormat (seconds) {
  var timeFormat = '';
  var min = Math.floor(seconds / 60);
  var sec = seconds % 60;

  if(min < 10) {
    timeFormat += "0";
  }
  timeFormat += min + ":";
  if(sec < 10) {
    timeFormat += "0";
  }
  timeFormat += sec.toFixed(0);
  return timeFormat;
}

function formatTime(date) {
  var year = date.getFullYear(),
    month = '' + (date.getMonth() + 1),
    day = '' + date.getDate(),
    hour = date.getHours(),
    minute = date.getMinutes();

  if (month.length < 2)
    month = '0' + month.toString();
  if (day.length < 2)
    day = '0' + day.toString();
  if (hour < 10)
    hour = '0' + hour.toString();
  if (minute < 10)
    minute = '0' + minute.toString();

  return [year, month, day].join('-') + ' ' + [hour, minute].join(':');
}

function formatDateTimeLocal(date) {
  var month = '' + (date.getMonth() + 1),
    day = '' + date.getDate(),
    year = date.getFullYear(),
    hour = date.getHours(),
    minute = date.getMinutes();

  if (month.length < 2)
    month = '0' + month.toString();
  if (day.length < 2)
    day = '0' + day.toString();
  if (hour < 10)
    hour = '0' + hour.toString();
  if (minute < 10)
    minute = '0' + minute.toString();

  return [year, month, day].join('-') + 'T' + [hour, minute].join(':');
}

function formatDateTime(date) {
  var month = '' + (date.getMonth() + 1),
    day = '' + date.getDate(),
    year = date.getFullYear(),
    hour = date.getHours(),
    minute = date.getMinutes(),
    second = date.getSeconds();

  if (month.length < 2)
    month = '0' + month.toString();
  if (day.length < 2)
    day = '0' + day.toString();
  if (hour < 10)
    hour = '0' + hour.toString();
  if (minute < 10)
    minute = '0' + minute.toString();
  if (second < 10)
    second = '0' + second.toString();

  return [year, month, day].join('-') + ' ' + [hour, minute, second].join(':');
}

function generateRandomCode(length) {
  var stringList = "azxcvbnmsdfghjklqwertyuiopZXCVBNMASDFGHJKLQWERTYUIOP0123456789";
  var code = "";
  for (let i = 0; i < length; i++) {
    let index = Math.floor(Math.random() * stringList.length);
    code += stringList.charAt(index);

  }
  return code;
}
